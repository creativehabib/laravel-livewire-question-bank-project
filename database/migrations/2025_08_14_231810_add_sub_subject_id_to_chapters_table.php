<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->foreignId('sub_subject_id')->nullable()->constrained('sub_subjects')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign(['sub_subject_id']);
            $table->dropColumn('sub_subject_id');
        });
    }
};
