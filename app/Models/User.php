<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_url',
        'department',
        'district',
        'upazila',
        'phone',
        'address',
        'role_confirmed_at',
        'teacher_profile_completed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'role_confirmed_at' => 'datetime',
            'teacher_profile_completed_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role === Role::TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->role === Role::STUDENT;
    }

    public function isOnline(): bool
    {
        $minutes = (int) Setting::get('chat_ai_admin_offline_minutes', config('chat.ai_admin_offline_minutes'));
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes($minutes)->getTimestamp())
            ->exists();
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->attributes['avatar_url'] ?? null;
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->map(fn (string $segment) => Str::upper(Str::substr($segment, 0, 1)))
            ->take(2)
            ->join('');
    }
}
