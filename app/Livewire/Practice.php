<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;
use App\Services\QuestionViewService;

class Practice extends Component
{
    public $current, $selectedOption;

    /**
     * Selected subject and chapter identifiers.
     */
    public $subjectId = '';
    public $chapterId = '';

    /**
     * Cached lists of subjects and chapters for the dropdowns.
     */
    public $subjects = [];
    public $chapters = [];

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
        $this->loadChapters();
        $this->loadRandom();
    }

    protected function loadChapters(): void
    {
        $this->chapters = $this->subjectId
            ? Chapter::where('subject_id', $this->subjectId)->orderBy('name')->get()
            : Chapter::orderBy('name')->get();
    }

    public function updatedSubjectId(): void
    {
        $this->chapterId = '';
        $this->loadChapters();
    }

    public function loadRandom()
    {
        $query = Question::with('options');

        if ($this->subjectId) {
            $query->where('subject_id', $this->subjectId);
        }

        if ($this->chapterId) {
            $query->where('chapter_id', $this->chapterId);
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

