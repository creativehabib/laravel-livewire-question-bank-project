<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use Illuminate\Console\Command;

class CleanOldChatMessages extends Command
{
    protected $signature = 'chat:clean';

    protected $description = 'Delete chat messages older than 30 days';

    public function handle(): int
    {
        $deleted = ChatMessage::where('created_at', '<', now()->subDays(30))->delete();
        $this->info("Deleted {$deleted} old chat messages.");
        return Command::SUCCESS;
    }
}
