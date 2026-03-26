<?php

namespace App\Livewire\Teacher;

use App\Models\Question;
use App\Models\SubSubject;
use Livewire\Component;
use App\Models\Subject;
use App\Models\Topic;
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
    public $topics = [];

    public $selectedClass = null;
    public $selectedSubject = null;
    public $selectedTopics = [];

    // Load initial data when the component mounts
    public function mount()
    {
        $this->classes = Subject::all();
    }

    // This runs when the 'selectedClass' property changes
    public function updatedSelectedClass($class_id)
    {
        if(!empty($class_id)) {
            $this->subjects = SubSubject::where('subject_id', $class_id)->get();
        } else {
            $this->subjects = [];
        }
        $this->selectedSubject = null;
        $this->topics = [];
        $this->selectedTopics = [];
    }

    // This runs when the 'selectedSubject' property changes
    public function updatedSelectedSubject($subject_id)
    {
        if(!empty($subject_id)) {
            $this->topics = Topic::where('sub_subject_id', $subject_id)->get();
        } else {
            $this->topics = [];
        }
        $this->selectedTopics = [];
    }

    // This method is called when the form is submitted
    public function saveQuestionSet()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selectedClass' => 'required|exists:subjects,id',
            'selectedSubject' => 'required|exists:sub_subjects,id',
            'selectedTopics' => 'required|array|min:1',
            'type' => 'required|in:mcq,cq,combine',
            'quantity' => 'required|integer|min:1',
        ]);

        // Prepare the generation criteria JSON data
        $criteria = [
            'subject_id' => $this->selectedClass,
            'sub_subject_id' => $this->selectedSubject,
            'topic_ids' => $this->selectedTopics,
            'type' => $this->type,
            'quantity' => $this->quantity
        ];

        // প্রশ্ন খুঁজে বের করা
        $questions = Question::whereIn('topic_id', $this->selectedTopics)
            ->inRandomOrder()
            ->limit($this->quantity)
            ->pluck('id');

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
