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

    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        // Persist the message immediately so both sender and recipient can
        // retrieve it without waiting for the flush command to run.
        $message = ChatMessage::create($this->payload);

        broadcast(new ChatMessageSent($message));
    }
}
