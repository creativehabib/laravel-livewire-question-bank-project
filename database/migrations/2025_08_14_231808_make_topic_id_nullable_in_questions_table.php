<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
        });

        DB::statement('ALTER TABLE questions MODIFY topic_id BIGINT UNSIGNED NULL');

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
        });

        DB::statement('ALTER TABLE questions MODIFY topic_id BIGINT UNSIGNED NOT NULL');

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnDelete();
        });
    }
};
