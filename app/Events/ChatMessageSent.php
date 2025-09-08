<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.' . $this->message->user_id),
        ];

        if ($this->message->recipient_id) {
            $channels[] = new PrivateChannel('chat.' . $this->message->recipient_id);
        } else {
            $channels[] = new PrivateChannel('chat-admins');
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return ['message' => $this->message->load('user')->toArray()];
    }

    public function broadcastAs(): string
    {
        return 'ChatMessageSent';
    }
}
