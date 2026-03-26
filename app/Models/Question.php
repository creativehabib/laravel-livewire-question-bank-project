<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subject_id',
        'sub_subject_id',
        'topic_id',
        'title',
        'description',
        'difficulty',
        'question_type',
        'marks',
        'extra_content',
        'slug',
        'views',
        'user_id',
    ];

    protected $casts = [
        'views' => 'integer',
        'marks' => 'float',
        'extra_content' => 'array',
    ];

    public function questionSets()
    {
        return $this->belongsToMany(QuestionSet::class, 'question_set_items')
            ->withPivot('order');
    }

    public function examCategories()
    {
        return $this->belongsToMany(ExamCategory::class, 'exam_category_question');
    }
    private function generateUniqueSlug($title): string
    {
        $baseSlug = Str::slug(Str::limit(strip_tags($title), 50, ''));
        if (!$baseSlug) {
            $baseSlug = 'question-' . time();
        }
        return $baseSlug . '-' . Str::random(4);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function subSubject(): BelongsTo
    {
        return $this->belongsTo(SubSubject::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
