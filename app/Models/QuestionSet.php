<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class QuestionSet extends Model
{
    protected $guarded = [];

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'generation_criteria' => 'array',
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_set_items')
            ->withPivot('order') // পিভট টেবিলের order কলামটি অ্যাক্সেস করার জন্য
            ->orderBy('pivot_order'); // order অনুযায়ী প্রশ্নগুলো সাজানোর জন্য
    }

    public function getRelatedSubject()
    {
        $subjectId = $this->generation_criteria['subject_id'] ?? null;
        return $subjectId ? Subject::find($subjectId) : null;
    }

    public function getRelatedChapter()
    {
        $chapterId = $this->generation_criteria['chapter_id'] ?? null;
        return $chapterId ? Chapter::find($chapterId) : null;
    }



    public function getRelatedTopics(): Collection
    {
        $topicIds = $this->generation_criteria['topic_ids'] ?? [];
        if (empty($topicIds)) {
            return collect();
        }
        return Topic::whereIn('id', $topicIds)->get();
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
    public function topics()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function findRelatedQuestions()
    {
        // ১. generation_criteria থেকে সমস্ত শর্ত নিন
        $criteria = $this->generation_criteria;

        // ২. শর্তগুলো থেকে প্রয়োজনীয় আইডি এবং তথ্য বের করুন
        $subjectId = $criteria['subject_id'] ?? null;
        $chapterId = $criteria['chapter_id'] ?? null; // নতুন শর্ত
        $topicIds = $criteria['topic_ids'] ?? [];
        $type = $criteria['type'] ?? 'mcq';
        $difficulty = $criteria['difficulty'] ?? null;
        $quantity = $criteria['quantity'] ?? 10;

        // যদি কোনো অধ্যায় আইডি না থাকে, তাহলে খালি কালেকশন রিটার্ন করুন
        if (empty($topicIds)) {
            return collect();
        }

        // ৩. এখন সব শর্ত দিয়ে 'questions' টেবিলে একটিমাত্র কুয়েরি তৈরি করুন
        $query = Question::where('type', $type)
            ->whereIn('topic_id', $topicIds);


        // 'topic' রিলেশনশিপ ব্যবহার করে subject এবং chapter অনুযায়ী ফিল্টার করুন
        if ($subjectId) {
            $query->whereHas('topic', function ($topicQuery) use ($subjectId, $chapterId) {

                // Topic-এর সাথে সম্পর্কিত Subject-এর উপর শর্ত
                $topicQuery->where('subject_id', $subjectId);

                // যদি chapter_id থাকে, তাহলে Subject-এর সাথে সম্পর্কিত Chapter-এর উপর শর্ত
                if ($chapterId) {
                    $topicQuery->whereHas('subject', function ($subjectQuery) use ($chapterId) {
                        // আপনার Subject ও Chapter মডেলের মধ্যে সম্পর্ক অনুযায়ী এখানে কুয়েরি করতে হবে
                        // উদাহরণস্বরূপ: $subjectQuery->where('chapter_id', $chapterId);
                    });
                }
            });
        }

        // ৪. quantity অনুযায়ী এলোমেলোভাবে প্রশ্নগুলো খুঁজে বের করে রিটার্ন করুন
        return $query->inRandomOrder()->limit($quantity)->get();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot() // (৩) নতুন ডেটা তৈরির সময় স্বয়ংক্রিয়ভাবে UUID সেট করার জন্য
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
