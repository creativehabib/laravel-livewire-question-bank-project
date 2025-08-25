<?php

namespace App\Jobs;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendChatMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array */
    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        $message = ChatMessage::create($this->payload);
        broadcast(new ChatMessageSent($message));
    }
}
