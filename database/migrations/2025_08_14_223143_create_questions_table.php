<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chapter_id')->nullable()->constrained('chapters')->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->cascadeOnDelete();
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();

            $table->longText('title');           // HTML from TinyMCE + MathType
            $table->longText('description')->nullable();
            $table->json('extra_content')->nullable();
            $table->decimal('marks', 8, 2)->default(0);
            $table->string('slug')->unique();
            $table->enum('difficulty', ['easy','medium','hard'])->default('easy');
            $table->enum('question_type', ['mcq','cq', 'short'])->default('mcq');
            $table->unsignedBigInteger('views')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
