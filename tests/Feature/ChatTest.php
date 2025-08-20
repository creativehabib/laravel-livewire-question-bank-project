<?php

namespace Tests\Feature;

use App\Livewire\Admin\Chat;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
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

        $this->assertDatabaseMissing('chat_messages', [
            'user_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => 'Hello there',
        ]);

        Artisan::call('chat:flush');

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

        $this->assertNotNull(ChatMessage::first()->fresh()->read_at);
    }
}
