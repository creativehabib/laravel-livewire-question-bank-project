<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_category_question');
    }
}
