<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $from;
    public int $to;

    public function __construct(int $from, int $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->to)];
    }

    public function broadcastWith(): array
    {
        return ['user_id' => $this->from];
    }

    public function broadcastAs(): string
    {
        return 'UserTyping';
    }
}

