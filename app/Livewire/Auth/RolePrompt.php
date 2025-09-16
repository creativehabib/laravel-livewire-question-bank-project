<?php

namespace App\Livewire\Auth;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RolePrompt extends Component
{
    public ?string $selectedRole = null;

    public bool $showRoleModal = false;
    public bool $showTeacherForm = false;

    public string $department = '';
    public string $district = '';
    public string $upazila = '';
    public string $phone = '';
    public string $address = '';

    public function mount(): void
    {
        $this->syncState();
    }

    public function submitRole(): void
    {
        $this->validate([
            'selectedRole' => ['required', Rule::in([Role::TEACHER->value, Role::STUDENT->value])],
        ]);

        $user = $this->currentUser();

        if (! $user) {
            return;
        }

        $role = Role::from($this->selectedRole);

        $payload = [
            'role' => $role,
            'role_confirmed_at' => now(),
        ];

        if ($role === Role::STUDENT) {
            $payload['teacher_profile_completed_at'] = null;
        }

        $user->forceFill($payload)->save();
        $user->refresh();

        $this->showRoleModal = false;

        if ($role === Role::TEACHER && ! $this->teacherProfileComplete($user)) {
            $this->fillTeacherFields($user);
            $this->showTeacherForm = true;
        } else {
            $this->resetTeacherFields();
            $this->showTeacherForm = false;
        }
    }

    public function submitTeacherForm(): void
    {
        $this->validate([
            'department' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'upazila' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
        ]);

        $user = $this->currentUser();

        if (! $user) {
            return;
        }

        $user->forceFill([
            'department' => $this->department,
            'district' => $this->district,
            'upazila' => $this->upazila,
            'phone' => $this->phone,
            'address' => $this->address,
            'teacher_profile_completed_at' => now(),
        ])->save();

        $user->refresh();
        $this->resetTeacherFields();
        $this->showTeacherForm = false;
    }

    public function render()
    {
        return view('livewire.auth.role-prompt');
    }

    protected function syncState(): void
    {
        $user = $this->currentUser();

        if (! $user || $user->role === Role::ADMIN) {
            $this->showRoleModal = false;
            $this->showTeacherForm = false;
            return;
        }

        $needsRoleConfirmation = $user->role_confirmed_at === null;
        $needsTeacherDetails = $user->role === Role::TEACHER && ! $this->teacherProfileComplete($user);

        $this->showRoleModal = $needsRoleConfirmation;
        $this->showTeacherForm = ! $needsRoleConfirmation && $needsTeacherDetails;

        $this->selectedRole = $needsRoleConfirmation ? null : $user->role?->value;

        if ($needsTeacherDetails) {
            $this->fillTeacherFields($user);
        } else {
            $this->resetTeacherFields();
        }
    }

    protected function fillTeacherFields(User $user): void
    {
        $this->department = (string) ($user->department ?? '');
        $this->district = (string) ($user->district ?? '');
        $this->upazila = (string) ($user->upazila ?? '');
        $this->phone = (string) ($user->phone ?? '');
        $this->address = (string) ($user->address ?? '');
    }

    protected function resetTeacherFields(): void
    {
        $this->department = '';
        $this->district = '';
        $this->upazila = '';
        $this->phone = '';
        $this->address = '';
    }

    protected function teacherProfileComplete(User $user): bool
    {
        return ! empty($user->department)
            && ! empty($user->district)
            && ! empty($user->upazila)
            && ! empty($user->phone)
            && ! empty($user->address)
            && ! empty($user->teacher_profile_completed_at);
    }

    protected function currentUser(): ?User
    {
        $user = Auth::user();

        return $user instanceof User ? $user : null;
    }
}
