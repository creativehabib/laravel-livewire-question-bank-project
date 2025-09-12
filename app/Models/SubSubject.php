<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubSubject extends Model
{
    protected $fillable = ['subject_id', 'name'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
