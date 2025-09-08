<?php

namespace App\Jobs;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

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
        $messages = Cache::get('chat.pending', []);
        $messages[] = $this->payload;
        Cache::put('chat.pending', $messages, 3600);

        $message = new ChatMessage($this->payload);
        broadcast(new ChatMessageSent($message));
    }
}
