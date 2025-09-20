<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\QuestionSet;
use App\Models\Question;
use Illuminate\Http\Request;

class ViewQuestions extends Component
{
    public QuestionSet $questionSet;
    public $availableQuestions;

    /**
     * URL থেকে পাওয়া qset আইডি দিয়ে কম্পোনেন্ট মাউন্ট হবে
     */
    public function mount(Request $request)
    {
        // ১. URL থেকে qset আইডি নিন এবং QuestionSet মডেলটি খুঁজুন
        $qsetId = $request->query('qset', 1); // ডিফল্ট আইডি ১ ধরা হলো
        $this->questionSet = QuestionSet::findOrFail($qsetId);


        // ৩. generation_criteria থেকে কুয়েরির জন্য প্রয়োজনীয় শর্তগুলো বের করুন
        $criteria = $this->questionSet->generation_criteria;

        $type = $criteria['type'] ?? 'mcq';
        $quantity = $criteria['quantity'] ?? 100;
        $chapterId = $criteria['chapter_id'] ?? null;
        $subjectId = $criteria['subject_id'] ?? null;
        $subSubjectId = $criteria['sub_subject_id'] ?? null;
        // $subSubjectId = $criteria['sub_subject_id'] ?? null; // প্রয়োজনে ব্যবহার করুন

        // ৪. এখন শর্তগুলো ব্যবহার করে questions টেবিলে কুয়েরি তৈরি করুন
        $query = Question::query();

        if ($type) {
            $query->where('question_type', $type);
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        if ($subSubjectId) {
            $query->where('sub_subject_id', $subSubjectId);
        }

        if ($chapterId) {
            $query->where('chapter_id', $chapterId);
        }

        $this->availableQuestions = $query->inRandomOrder()->limit($quantity)->get();
    }

    public function render()
    {
        return view('livewire.teacher.view-questions')
            ->layout('layouts.admin');
    }
}
