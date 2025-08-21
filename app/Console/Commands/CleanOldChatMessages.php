<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use App\Models\Setting;
use Illuminate\Console\Command;

class CleanOldChatMessages extends Command
{
    protected $signature = 'chat:clean';

    protected $description = 'Delete chat messages older than configured retention period';

    public function handle(): int
    {
        $days = Setting::get('chat_retention_days', config('chat.retention_days'));
        $deleted = ChatMessage::where('created_at', '<', now()->subDays($days))->delete();
        $this->info("Deleted {$deleted} old chat messages.");
        return Command::SUCCESS;
    }
}
