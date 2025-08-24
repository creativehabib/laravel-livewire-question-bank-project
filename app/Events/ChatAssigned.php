<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat-assigned.' . $this->chat->user_id)];
    }

    public function broadcastWith(): array
    {
        return ['admin_id' => $this->chat->assigned_admin_id];
    }

    public function broadcastAs(): string
    {
        return 'ChatAssigned';
    }
}

