<?php

namespace App\Jobs;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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

        try {
            broadcast(new ChatMessageSent($message));
        } catch (BroadcastException $e) {
            Log::warning('Failed to broadcast chat message.', [
                'message_id' => $message->id,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
