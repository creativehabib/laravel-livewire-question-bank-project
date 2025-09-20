<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\QuestionSet;

class GeneratedQuestionSetPage extends Component
{
    public QuestionSet $questionSet;

    public $subject;
    public $subSubject;
    public $chapter;

    // URL থেকে পাওয়া আইডি দিয়ে কম্পোনেন্ট মাউন্ট হবে
    public function mount($qset)
    {
        // আইডি দিয়ে QuestionSet এবং এর সাথে সম্পর্কিত Class খুঁজে বের করুন
        $this->questionSet = QuestionSet::with('user')->findOrFail($qset);
        $this->subject = $this->questionSet->getRelatedSubject();
        $this->subSubject = $this->questionSet->getRelatedSubSubject();
        $this->chapter = $this->questionSet->getRelatedChapter();
    }

    public function render()
    {
        return view('livewire.teacher.generated-question-set-page')
            ->layout('layouts.panel');
    }
}

