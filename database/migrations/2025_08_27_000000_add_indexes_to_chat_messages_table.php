<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->index(['user_id', 'recipient_id']);
            $table->index('seen_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'recipient_id']);
            $table->dropIndex(['seen_at']);
            $table->dropIndex(['created_at']);
        });
    }
};

