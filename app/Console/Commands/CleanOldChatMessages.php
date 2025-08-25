<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use Illuminate\Console\Command;

class CleanOldChatMessages extends Command
{
    protected $signature = 'chat:clean';

    protected $description = 'Delete chat messages older than configured retention period';

    public function handle(): int
    {
        $deleted = ChatMessage::pruneAll();
        $this->info("Deleted {$deleted} old chat messages.");
        return Command::SUCCESS;
    }
}
