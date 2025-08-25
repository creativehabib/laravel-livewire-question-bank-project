<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Livewire\ChatPopup;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatPopupTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_message_admin(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $teacher = User::factory()->create(['role' => Role::TEACHER]);

        Chat::create(['user_id' => $teacher->id, 'assigned_admin_id' => $admin->id]);

        $this->actingAs($teacher);

        Livewire::test(ChatPopup::class)
            ->set('message', 'Hello Admin')
            ->call('send')
            ->assertSet('message', '');

        $this->assertDatabaseHas('chat_messages', [
            'user_id' => $teacher->id,
            'recipient_id' => $admin->id,
            'message' => 'Hello Admin',
        ]);
    }

    public function test_student_sees_admin_reply_without_reload(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $student = User::factory()->create(['role' => Role::TEACHER]);

        $this->actingAs($student);

        $component = Livewire::test(ChatPopup::class);
        $component->set('message', 'Hi')->call('send');

        Chat::where('user_id', $student->id)->update(['assigned_admin_id' => $admin->id]);
        ChatMessage::where('user_id', $student->id)->update(['recipient_id' => $admin->id]);
        ChatMessage::create([
            'user_id' => $admin->id,
            'recipient_id' => $student->id,
            'message' => 'Hello',
            'created_at' => now(),
        ]);

        $component->call('$refresh')
            ->assertSee('Hi')
            ->assertSee('Hello');
    }
}

