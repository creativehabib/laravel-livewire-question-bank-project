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
        $hours = (int) Setting::get('chat_retention_hours', config('chat.retention_hours'));

        $deleted = ChatMessage::where('created_at', '<', now()->subHours($hours))->delete();

        $this->info("Deleted {$deleted} old chat messages.");

        return Command::SUCCESS;
    }
}
