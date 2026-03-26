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
        Schema::create('exam_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., BCS, Admission, Class 9
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // ২. পিভট টেবিল (যেটি প্রশ্ন এবং ক্যাটাগরিকে যুক্ত করবে)
        Schema::create('exam_category_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_category_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_category_question'); // আগে পিভট টেবিল
        Schema::dropIfExists('exam_categories'); // তারপর মেইন টেবিল
    }
};
