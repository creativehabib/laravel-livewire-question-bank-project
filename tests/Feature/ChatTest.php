<?php

namespace Tests\Feature;

use App\Livewire\Admin\Chat;
use App\Livewire\ChatPopup;
use App\Models\User;
use App\Models\ChatMessage;
use App\Models\Setting;
use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_send_messages(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->actingAs($sender);

        Livewire::test(Chat::class)
            ->set('recipient_id', $recipient->id)
            ->set('message', 'Hello there')
            ->call('send')
            ->assertSet('message', '');

        $this->assertDatabaseHas('chat_messages', [
            'user_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => 'Hello there',
        ]);
    }

    public function test_unread_count_clears_after_viewing_messages(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        ChatMessage::create([
            'user_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => 'Hi',
        ]);

        $this->actingAs($recipient);

        Livewire::test(Chat::class)
            ->assertViewHas('messageCounts', function ($counts) use ($sender) {
                return $counts[$sender->id] === 1;
            })
            ->set('recipient_id', $sender->id)
            ->assertViewHas('messageCounts', function ($counts) use ($sender) {
                return ($counts[$sender->id] ?? 0) === 0;
            });

        $this->assertNotNull(ChatMessage::first()->fresh()->seen_at);
    }

    public function test_message_respects_max_length_setting(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Setting::set('chat_message_max_length', 5);

        $this->actingAs($sender);

        Livewire::test(Chat::class)
            ->set('recipient_id', $recipient->id)
            ->set('message', 'toolong')
            ->call('send')
            ->assertHasErrors(['message' => 'max']);
    }

    public function test_daily_message_limit_is_enforced(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Setting::set('chat_daily_message_limit', 1);

        $this->actingAs($sender);

        Livewire::test(Chat::class)
            ->set('recipient_id', $recipient->id)
            ->set('message', 'first')
            ->call('send')
            ->assertSet('message', '');

        Livewire::test(Chat::class)
            ->set('recipient_id', $recipient->id)
            ->set('message', 'second')
            ->call('send')
            ->assertHasErrors(['message']);
    }

    public function test_admin_can_send_unlimited_messages(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $recipient = User::factory()->create();

        Setting::set('chat_daily_message_limit', 1);

        $this->actingAs($admin);

        Livewire::test(Chat::class)
            ->set('recipient_id', $recipient->id)
            ->set('message', 'first')
            ->call('send')
            ->assertSet('message', '');

        Livewire::test(Chat::class)
            ->set('recipient_id', $recipient->id)
            ->set('message', 'second')
            ->call('send')
            ->assertSet('message', '');
    }

    public function test_unassigned_messages_show_notification_and_assign_on_reply(): void
    {
        $admin = User::factory()->create();
        $student = User::factory()->create();

        $this->actingAs($student);
        Livewire::test(ChatPopup::class)
            ->set('message', 'Help me')
            ->call('send');

        $this->actingAs($admin);

        Livewire::test(Chat::class)
            ->assertViewHas('messageCounts', function ($counts) use ($student) {
                return ($counts[$student->id] ?? 0) === 1;
            })
            ->set('recipient_id', $student->id)
            ->assertViewHas('messageCounts', function ($counts) use ($student) {
                return ($counts[$student->id] ?? 0) === 0;
            });

        $this->assertDatabaseHas('chat_messages', [
            'user_id' => $student->id,
            'recipient_id' => $admin->id,
        ]);
    }

    public function test_chat_clean_command_removes_old_messages(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $message = ChatMessage::create([
            'user_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => 'old',
        ]);
        $message->created_at = now()->subDays(40);
        $message->save();

        $this->artisan('chat:clean')->assertExitCode(0);

        $this->assertDatabaseMissing('chat_messages', ['id' => $message->id]);
    }

    public function test_chat_clean_command_respects_custom_retention_setting(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Setting::set('chat_retention_hours', 1);

        $recent = ChatMessage::create([
            'user_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => 'recent',
            'created_at' => now()->subMinutes(30),
        ]);

        $old = ChatMessage::create([
            'user_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => 'old',
            'created_at' => now()->subHours(2),
        ]);

        $this->artisan('chat:clean')->assertExitCode(0);

        $this->assertDatabaseHas('chat_messages', ['id' => $recent->id]);
        $this->assertDatabaseMissing('chat_messages', ['id' => $old->id]);
    }
}
