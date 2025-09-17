<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use App\Livewire\Admin\Users\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ManageUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_edit_modal_and_update_user(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $user = User::factory()->create([
            'role' => Role::STUDENT,
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'phone' => '0123456789',
            'address' => 'Old Address',
            'institution_name' => 'Old Institution',
            'division' => 'Old Division',
            'district' => 'Old District',
            'thana' => 'Old Thana',
        ]);

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->call('editUser', $user->id)
            ->assertSet('showEditModal', true)
            ->assertDispatched('open-modal', fn ($payload) => $payload === 'edit-user')
            ->set('editForm.name', 'Updated Name')
            ->set('editForm.email', 'updated@example.com')
            ->set('editForm.role', Role::TEACHER->value)
            ->set('editForm.phone', '0987654321')
            ->set('editForm.address', 'New Address')
            ->set('editForm.institution_name', 'New Institution')
            ->set('editForm.division', 'New Division')
            ->set('editForm.district', 'New District')
            ->set('editForm.thana', 'New Thana')
            ->call('updateUser')
            ->assertDispatched('userUpdated')
            ->assertSet('showEditModal', false)
            ->assertDispatched('close-modal', fn ($payload) => $payload === 'edit-user');

        $user->refresh();

        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('updated@example.com', $user->email);
        $this->assertEquals(Role::TEACHER, $user->role);
        $this->assertSame('0987654321', $user->phone);
        $this->assertSame('New Address', $user->address);
        $this->assertSame('New Institution', $user->institution_name);
        $this->assertSame('New Division', $user->division);
        $this->assertSame('New District', $user->district);
        $this->assertSame('New Thana', $user->thana);
    }
}
