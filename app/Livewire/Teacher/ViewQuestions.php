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

        // ১. QuestionSet লোড করুন এবং এর সাথে যুক্ত প্রশ্নগুলোও নিয়ে আসুন
        $this->questionSet = QuestionSet::findOrFail($qsetId);

        $this->selectedQuestions = $this->questionSet->questions->pluck('id')->map(fn ($id) => (string) $id)->toArray();

        // ৩. generation_criteria থেকে শর্তগুলো বের করুন
        $criteria = $this->questionSet->generation_criteria;
        $type = $criteria['type'] ?? 'mcq';
        $quantity = $criteria['quantity'] ?? 100;
        $subjectId = $criteria['subject_id'] ?? null;
        $subSubjectId = $criteria['sub_subject_id'] ?? null;
        $topicId = $criteria['topic_id'] ?? null;

        // ৪. শর্ত অনুযায়ী প্রশ্ন খুঁজুন
        $this->availableQuestions = Question::query()
            ->with('options', 'tags') // options রাখা হয়েছে শুধুমাত্র পুরানো ডাটা সাপোর্ট করার জন্য
            ->when($type, function ($q) use ($type) {
                // ✅ Combine/Composite এর জন্য স্মার্ট ফিল্টার লজিক
                if ($type === 'mcq') {
                    $q->where('question_type', 'mcq');
                } elseif ($type === 'creative' || $type === 'cq') {
                    $q->where('question_type', 'cq');
                } elseif ($type === 'short') {
                    $q->where('question_type', 'short');
                } elseif (in_array($type, ['composite', 'combine'])) {
                    // 'combine' সিলেক্ট করলে ডাটাবেজ থেকে সব ধরনের প্রশ্ন (MCQ, CQ, Short) নিয়ে আসবে
                    $q->whereIn('question_type', ['mcq', 'cq', 'short']);
                } else {
                    $q->where('question_type', $type);
                }
            })
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->when($subSubjectId, fn ($q) => $q->where('sub_subject_id', $subSubjectId))
            ->when($topicId, fn ($q) => $q->where('topic_id', $topicId))
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

    public function saveSelection()
    {
        // ১. sync করার জন্য ডেটাটিকে সঠিক ফরম্যাটে সাজিয়ে নিন
        $dataToSync = [];
        $order = 1;

        foreach ($this->selectedQuestions as $questionId) {
            $dataToSync[$questionId] = ['order' => $order++];
        }

        // ২. sync() মেথডে নতুন ফরম্যাটের ডেটা পাস করুন
        // এটি question_set_items টেবিলে question_id এর সাথে order ও সেভ করবে
        $this->questionSet->questions()->sync($dataToSync);

        session()->flash('success', count($this->selectedQuestions) . 'টি প্রশ্ন সফলভাবে সেভ করা হয়েছে!');
    }

    public function render()
    {
        return view('livewire.teacher.view-questions')
            ->layout('layouts.admin');
    }
}
