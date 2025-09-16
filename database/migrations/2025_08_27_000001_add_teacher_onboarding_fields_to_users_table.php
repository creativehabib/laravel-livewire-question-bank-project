<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('role_confirmed_at')->nullable()->after('role');
            $table->string('department')->nullable()->after('avatar_url');
            $table->string('district')->nullable()->after('department');
            $table->string('upazila')->nullable()->after('district');
            $table->string('phone')->nullable()->after('upazila');
            $table->text('address')->nullable()->after('phone');
            $table->timestamp('teacher_profile_completed_at')->nullable()->after('address');
        });

        DB::table('users')->whereNull('role_confirmed_at')->update([
            'role_confirmed_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role_confirmed_at',
                'department',
                'district',
                'upazila',
                'phone',
                'address',
                'teacher_profile_completed_at',
            ]);
        });
    }
};
