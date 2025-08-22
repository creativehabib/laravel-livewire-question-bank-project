<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use App\Livewire\Admin\Users\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ManageUserRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $user = User::factory()->create(['role' => Role::STUDENT]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->call('changeRole', $user->id, Role::TEACHER->value);

        $this->assertEquals(Role::TEACHER, $user->fresh()->role);
    }
}
