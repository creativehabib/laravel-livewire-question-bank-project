<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (! Schema::hasColumn('users', 'role_confirmed_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('role_confirmed_at')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'avatar_url')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar_url')->nullable();
            });
        }

        foreach (['institution_name', 'division', 'district', 'thana', 'phone'] as $column) {
            if (! Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->string($column)->nullable();
                });
            }
        }

        if (! Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('address')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'teacher_profile_completed_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('teacher_profile_completed_at')->nullable();
            });
        }

        if (Schema::hasColumn('users', 'role_confirmed_at')) {
            DB::table('users')
                ->whereNull('role_confirmed_at')
                ->update(['role_confirmed_at' => now()]);
        }
    }

    public function down(): void
    {
        // Intentionally left blank. The original migrations handle column removal.
    }
};
