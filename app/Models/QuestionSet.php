<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'generation_criteria' => 'array',
    ];

    public function getRelatedSubject()
    {
        $subjectId = $this->generation_criteria['subject_id'] ?? null;
        return $subjectId ? Subject::find($subjectId) : null;
    }

    public function getRelatedSubSubject()
    {
        $subSubjectId = $this->generation_criteria['sub_subject_id'] ?? null;
        return $subSubjectId ? SubSubject::find($subSubjectId) : null;
    }

    public function getRelatedChapter()
    {
        $chapterId = $this->generation_criteria['chapter_id'] ?? null;
        return $chapterId ? Chapter::find($chapterId) : null;
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
        $subSubjectId = $criteria['sub_subject_id'] ?? null; // নতুন শর্ত
        $chapterIds = $criteria['chapter_ids'] ?? [];
        $type = $criteria['type'] ?? 'mcq';
        $difficulty = $criteria['difficulty'] ?? null;
        $quantity = $criteria['quantity'] ?? 10;

        // যদি কোনো অধ্যায় আইডি না থাকে, তাহলে খালি কালেকশন রিটার্ন করুন
        if (empty($chapterIds)) {
            return collect();
        }

        // ৩. এখন সব শর্ত দিয়ে 'questions' টেবিলে একটিমাত্র কুয়েরি তৈরি করুন
        $query = Question::where('type', $type)
            ->whereIn('chapter_id', $chapterIds);


        // 'chapter' রিলেশনশিপ ব্যবহার করে subject এবং sub_subject অনুযায়ী ফিল্টার করুন
        if ($subjectId) {
            $query->whereHas('chapter', function ($chapterQuery) use ($subjectId, $subSubjectId) {

                // Chapter-এর সাথে সম্পর্কিত Subject-এর উপর শর্ত
                $chapterQuery->where('subject_id', $subjectId);

                // যদি sub_subject_id থাকে, তাহলে Subject-এর সাথে সম্পর্কিত SubSubject-এর উপর শর্ত
                if ($subSubjectId) {
                    $chapterQuery->whereHas('subject', function ($subjectQuery) use ($subSubjectId) {
                        // আপনার Subject ও SubSubject মডেলের মধ্যে সম্পর্ক অনুযায়ী এখানে কুয়েরি করতে হবে
                        // উদাহরণস্বরূপ: $subjectQuery->where('sub_subject_id', $subSubjectId);
                    });
                }
            });
        }

        // ৪. quantity অনুযায়ী এলোমেলোভাবে প্রশ্নগুলো খুঁজে বের করে রিটার্ন করুন
        return $query->inRandomOrder()->limit($quantity)->get();
    }

}
