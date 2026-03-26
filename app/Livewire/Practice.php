<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use App\Services\QuestionViewService;

class Practice extends Component
{
    public $current, $selectedOption;

    /**
     * Selected subject and topic identifiers.
     */
    public $subjectId = '';
    public $topicId = '';

    /**
     * Cached lists of subjects and topics for the dropdowns.
     */
    public $subjects = [];
    public $topics = [];

    /**
     * Service used to record unique question views.
     */
    protected QuestionViewService $views;

    /**
     * Bootstrap the component with the view service on every request.
     */
    public function boot(QuestionViewService $views): void
    {
        $this->views = $views;
    }

    public function mount(): void
    {
        $this->subjects = Subject::orderBy('name')->get();
        $this->loadTopics();
        $this->loadRandom();
    }

    protected function loadTopics(): void
    {
        $this->topics = $this->subjectId
            ? Topic::where('subject_id', $this->subjectId)->orderBy('name')->get()
            : Topic::orderBy('name')->get();
    }

    public function updatedSubjectId(): void
    {
        $this->topicId = '';
        $this->loadTopics();
    }

    public function loadRandom()
    {
        $query = Question::with('options');

        if ($this->subjectId) {
            $query->where('subject_id', $this->subjectId);
        }

        if ($this->topicId) {
            $query->where('topic_id', $this->topicId);
        }

        $this->current = $query->inRandomOrder()->first();
        if ($this->current) {
            $this->views->record($this->current, request()->ip());
        }
        $this->selectedOption = null;
    }

    public function selectOption($id)
    {
        $this->selectedOption = $id;
    }

    public function render()
    {
        return view('livewire.practice')->layout('layouts.admin', ['title' => 'Practice']);
    }
}

