<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    protected $fillable = ['question_id','option_text','is_correct'];

    // JSON → array auto convert
    protected $casts = [
        'option_text' => 'array',
        'is_correct' => 'boolean',
    ];
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
