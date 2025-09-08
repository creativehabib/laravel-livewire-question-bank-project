<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ProcessCachedMessages extends Command
{
    protected $signature = 'chat:process-cached';
    protected $description = 'Persist cached chat messages from Redis to the database';

    public function handle(): int
    {
        $messages = [];
        while ($payload = Redis::lpop('pending_chat_messages')) {
            $messages[] = json_decode($payload, true);
        }

        if ($messages) {
            ChatMessage::insert($messages);
            $this->info(count($messages).' messages inserted.');
        } else {
            $this->info('No messages to process.');
        }

        return self::SUCCESS;
    }
}
