<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\QuestionSet;
use App\Models\Question;
use Illuminate\Http\Request;

class ViewQuestions extends Component
{
    // UI State Properties
    public $showExplanationFor = null;
    
    public QuestionSet $questionSet;
    public $availableQuestions;
    public $selectedQuestions = [];

      // Filter Properties
    public $searchKeyword = '';
    public $specialFilters = [];
    public $selectedTopics = [];
    
    public $allTopics = []; // To hold topics for the filter sidebar

    public function mount(Request $request)
    {
        $qsetId = $request->query('qset');

        // ১. QuestionSet লোড করুন এবং এর সাথে যুক্ত প্রশ্নগুলোও নিয়ে আসুন (Eager Loading)
        $this->questionSet = QuestionSet::findOrFail($qsetId);


        // ৩. generation_criteria থেকে শর্তগুলো বের করুন
        $criteria = $this->questionSet->generation_criteria;
        $type = $criteria['type'] ?? 'mcq';
        $quantity = $criteria['quantity'] ?? 100;
        $subjectId = $criteria['subject_id'] ?? null;
        $subSubjectId = $criteria['sub_subject_id'] ?? null;
        $chapterId = $criteria['chapter_id'] ?? null;

        // ৪. শর্ত অনুযায়ী প্রশ্ন খুঁজুন এবং availableQuestions প্রোপার্টিতে রাখুন
        $this->availableQuestions = Question::query()
            ->with('options','tags')
            ->when($type, fn ($q) => $q->where('question_type', $type)) // 'question_type' হলে সেটি দিন
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->when($subSubjectId, fn ($q) => $q->where('sub_subject_id', $subSubjectId))
            ->when($chapterId, fn ($q) => $q->where('chapter_id', $chapterId))


            ->inRandomOrder()
            ->limit($quantity)
            ->get();
    }

    /**
     * "Select All" বাটনে ক্লিক করলে এই মেথডটি কাজ করবে
     */
    public function toggleSelectAll()
    {
        if (count($this->selectedQuestions) === count($this->availableQuestions)) {
            $this->selectedQuestions = []; // সব সিলেক্ট করা থাকলে, সব আনসিলেক্ট করুন
        } else {
            // সব প্রশ্ন সিলেক্ট করুন
            $this->selectedQuestions = $this->availableQuestions->pluck('id')->toArray();
        }
    }

    public function toggleExplanation($questionId)
    {
        if ($this->showExplanationFor === $questionId) {
            $this->showExplanationFor = null;
        } else {
            $this->showExplanationFor = $questionId;
        }
    }

    public function toggleSelection($questionId)
    {
        // প্রশ্নটির আইডি int হিসেবে কনভার্ট করে নিন
        $questionId = (int) $questionId;

        // চেক করুন আইডিটি ইতোমধ্যে সিলেক্টেড অ্যারেতে আছে কি না
        if (in_array($questionId, $this->selectedQuestions)) {
            // যদি থাকে, তাহলে অ্যারে থেকে বাদ দিন (আনসিলেক্ট)
            $this->selectedQuestions = array_diff($this->selectedQuestions, [$questionId]);
        } else {
            // যদি না থাকে, তাহলে অ্যারেতে যোগ করুন (সিলেক্ট)
            $this->selectedQuestions[] = $questionId;
        }
    }

    /**
     * "Save" বাটনে ক্লিক করলে এই মেথডটি কাজ করবে
     */
    public function saveSelection()
    {
        // sync() মেথডটি পিভট টেবিলে শুধুমাত্র নির্বাচিত প্রশ্নগুলো রাখবে
        $this->questionSet->questions()->sync($this->selectedQuestions);

        session()->flash('success', count($this->selectedQuestions) . 'টি প্রশ্ন সফলভাবে সেভ করা হয়েছে!');
    }
    public function render()
    {
        return view('livewire.teacher.view-questions')
            ->layout('layouts.admin');
    }
}
