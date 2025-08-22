<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function changeRole(int $userId, string $role): void
    {
        $user = User::findOrFail($userId);
        $user->update(['role' => $role]);
        $this->dispatch('roleUpdated', message: 'Role updated successfully.');
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
