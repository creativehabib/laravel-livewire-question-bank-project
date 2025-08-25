<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class ChatMessage extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'user_id',
        'recipient_id',
        'message',
        'delivered_at',
        'seen_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'seen_at' => 'datetime',
    ];

    public function prunable()
    {
        $hours = Setting::get('chat_retention_hours', config('chat.retention_hours'));
        return static::where('created_at', '<', now()->subHours($hours));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
