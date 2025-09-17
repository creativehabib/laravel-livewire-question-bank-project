<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showEditModal = false;
    public bool $showSuspendModal = false;

    public ?User $editingUser = null;
    public ?User $suspendingUser = null;

    /**
     * @var array<string, mixed>
     */
    public array $editForm = [
        'name' => '',
        'email' => '',
        'role' => '',
        'phone' => '',
        'address' => '',
        'institution_name' => '',
        'division' => '',
        'district' => '',
        'thana' => '',
    ];

    /**
     * @var array<string, string>
     */
    public array $suspendForm = [
        'until' => '',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function changeRole(int $userId, string $role): void
    {
        $user = User::findOrFail($userId);
        $roleEnum = Role::tryFrom($role);

        if (! $roleEnum) {
            $this->dispatch('roleUpdated', message: 'Invalid role selected.', type: 'error');
            return;
        }

        $user->update(['role' => $roleEnum]);
        $this->dispatch('roleUpdated', message: 'Role updated successfully.');
    }

    public function editUser(int $userId): void
    {
        $this->editingUser = User::findOrFail($userId);
        $this->editForm = [
            'name' => (string) $this->editingUser->name,
            'email' => (string) $this->editingUser->email,
            'role' => $this->editingUser->role?->value ?? Role::STUDENT->value,
            'phone' => (string) ($this->editingUser->phone ?? ''),
            'address' => (string) ($this->editingUser->address ?? ''),
            'institution_name' => (string) ($this->editingUser->institution_name ?? ''),
            'division' => (string) ($this->editingUser->division ?? ''),
            'district' => (string) ($this->editingUser->district ?? ''),
            'thana' => (string) ($this->editingUser->thana ?? ''),
        ];

        $this->resetValidation();
        $this->showEditModal = true;
    }

    public function updateUser(): void
    {
        if (! $this->editingUser) {
            return;
        }

        $data = Validator::make($this->editForm, $this->editRules())->validate();
        $data['role'] = Role::from($data['role']);

        $this->editingUser->update($data);

        $this->dispatch('userUpdated', message: 'User updated successfully.');
        $this->closeEditModal();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetValidation();
        $this->resetEditForm();
    }

    public function openSuspendModal(int $userId): void
    {
        $this->suspendingUser = User::findOrFail($userId);
        $this->suspendForm['until'] = $this->suspendingUser->suspended_until
            ? $this->suspendingUser->suspended_until->format('Y-m-d\\TH:i')
            : '';

        $this->resetValidation();
        $this->showSuspendModal = true;
    }

    public function saveSuspension(): void
    {
        if (! $this->suspendingUser) {
            return;
        }

        $data = Validator::make($this->suspendForm, $this->suspendRules())->validate();

        $until = Carbon::parse($data['until']);
        $this->suspendingUser->update(['suspended_until' => $until]);

        $this->dispatch('userSuspensionUpdated', message: 'User suspended successfully.');
        $this->closeSuspendModal();
    }

    public function closeSuspendModal(): void
    {
        $this->showSuspendModal = false;
        $this->resetValidation();
        $this->resetSuspendForm();
    }

    public function clearSuspension(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['suspended_until' => null]);

        if ($this->suspendingUser && $this->suspendingUser->is($user)) {
            $this->closeSuspendModal();
        }

        $this->dispatch('userSuspensionUpdated', message: 'User suspension cleared.');
    }

    #[On('deleteUserConfirmed')]
    public function deleteUserConfirmed(int $userId): void
    {
        $user = User::find($userId);

        if (! $user) {
            return;
        }

        if (auth()->id() === $userId) {
            $this->dispatch('userDeleted', message: 'You cannot delete your own administrator account.', type: 'error');
            return;
        }

        $user->delete();

        if ($this->editingUser && $this->editingUser->is($user)) {
            $this->closeEditModal();
        }

        if ($this->suspendingUser && $this->suspendingUser->is($user)) {
            $this->closeSuspendModal();
        }

        $this->dispatch('userDeleted', message: 'User deleted successfully.');
    }

    protected function editRules(): array
    {
        $userId = $this->editingUser?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'role' => ['required', Rule::enum(Role::class)],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'thana' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function suspendRules(): array
    {
        return [
            'until' => ['required', 'date', 'after:now'],
        ];
    }

    protected function resetEditForm(): void
    {
        $this->editingUser = null;
        $this->editForm = [
            'name' => '',
            'email' => '',
            'role' => '',
            'phone' => '',
            'address' => '',
            'institution_name' => '',
            'division' => '',
            'district' => '',
            'thana' => '',
        ];
    }

    protected function resetSuspendForm(): void
    {
        $this->suspendingUser = null;
        $this->suspendForm = [
            'until' => '',
        ];
    }

    public function render()
    {
        $users = User::when($this->search, function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => Role::cases(),
        ])->layout('layouts.admin', ['title' => 'Users']);
    }
}
