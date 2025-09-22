<?php

namespace App\Livewire\Teacher;

use App\Models\SubSubject;
use Livewire\Component;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\QuestionSet;

class CreateQuestionSet extends Component
{
    // Form input properties
    public $name;
    public $type = 'mcq';
    public $quantity = 100;

    // Properties for dynamic dropdowns
    public $classes = [];
    public $subjects = [];
    public $chapters = [];

    public $selectedClass = null;
    public $selectedSubject = null;
    public $selectedChapter = null;

    // Load initial data when the component mounts
    public function mount()
    {
        $this->classes = Subject::all();
    }

    // This runs when the 'selectedClass' property changes
    public function updatedSelectedClass($class_id)
    {
        $this->subjects = SubSubject::where('subject_id', $class_id)->get();
        $this->selectedSubject = null; // Reset subject
        $this->chapters = []; // Reset chapters
    }

    // This runs when the 'selectedSubject' property changes
    public function updatedSelectedSubject($subject_id)
    {
        $this->chapters = Chapter::where('sub_subject_id', $subject_id)->get();
        $this->selectedChapter = null; // Reset chapter
    }

    // This method is called when the form is submitted
    public function saveQuestionSet()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selectedClass' => 'required|exists:subjects,id',
            'selectedSubject' => 'required|exists:sub_subjects,id',
            'selectedChapter' => 'required|exists:chapters,id',
            'type' => 'required|in:mcq,cq,combine',
            'quantity' => 'required|integer|min:1',
        ]);

        // Prepare the generation criteria JSON data
        $criteria = [
            'subject_id' => $this->selectedClass,
            'sub_subject_id' => $this->selectedSubject,
            'chapter_id' => $this->selectedChapter,
            'type' => $this->type,
            'quantity' => $this->quantity
        ];

        // Create the record in the 'question_sets' table
        $newQuestionSet = QuestionSet::create([
            'name' => $this->name,
            'user_id' => auth()->id(),
            'generation_criteria' => $criteria,
        ]);

        return redirect()->route('qset.generated', ['qset' => $newQuestionSet->id]);
    }

    public function render()
    {
        return view('livewire.teacher.question-set-create')
            ->layout('layouts.admin', ['title' => __('প্রশ্ন ক্রিয়েট')]); // Assuming you have a main layout file
    }
}
