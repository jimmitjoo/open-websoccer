<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class UserRoleManager extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedUser = null;
    public $selectedRole = '';

    public function mount()
    {
        if (!auth()->user()->role === Role::ADMIN) {
            abort(403);
        }
    }

    public function updateRole(User $user, $role)
    {
        if (!in_array($role, [Role::ADMIN, Role::MANAGER, Role::USER])) {
            return;
        }

        $user->update(['role' => $role]);
        $this->dispatch('role-updated');
    }

    public function render()
    {
        $users = User::where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->paginate(10);

        return view('livewire.admin.user-role-manager', [
            'users' => $users,
            'roles' => [Role::ADMIN, Role::MANAGER, Role::USER]
        ]);
    }
}
