<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\QuestionSet;
use Illuminate\Http\Request;

class QuestionPaper extends Component
{
    public QuestionSet $questionSet;

    // হেডার তথ্য রাখার জন্য প্রোপার্টি
    public $instituteName;
    public $subject;
    public $subSubject;
    public $chapter;

    public array $previewOptions = [
        'attachAnswerSheet' => false,
        'attachOmrSheet' => false,
        'markImportant' => false,
        'showQuestionInfo' => true,
        'showSubSubject' => true,
        'showChapter' => true,
        'showSetCode' => true,
        'showStudentInfo' => false,
        'showMarksBox' => false,
        'showInstructions' => true,
        'showNotice' => true,
        'showExamName' => false,
    ];

    public function mount(Request $request)
    {
        $qsetId = $request->query('qset');

        // questions রিলেশনশিপটি Eager Load করুন
        // এবং পিভট টেবিলের 'order' কলাম অনুযায়ী প্রশ্নগুলো সাজিয়ে নিন
        $this->questionSet = QuestionSet::with(['questions' => function ($query) {
                                $query->orderBy('pivot_order', 'asc');
                            },'user'])
                            ->findOrFail($qsetId);
        $this->subject = $this->questionSet->getRelatedSubject();
        $this->subSubject = $this->questionSet->getRelatedSubSubject();
        $this->chapter = $this->questionSet->getRelatedChapter();
        $this->instituteName = $this->questionSet->user->institution_name;
    }

    public function render()
    {
        return view('livewire.teacher.question-paper')
               ->layout('layouts.admin');
    }
}
