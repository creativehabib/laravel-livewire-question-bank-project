<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class FlushChatCache extends Command
{
    protected $signature = 'chat:flush';

    protected $description = 'Store cached chat messages into the database';

    public function handle(): int
    {
        $messages = Cache::pull('chat.pending', []);
        foreach ($messages as $message) {
            ChatMessage::create($message);
        }
        $this->info('Stored '.count($messages).' cached chat messages.');
        return Command::SUCCESS;
    }
}
