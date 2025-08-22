<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{Question, Subject, Chapter};
use App\Services\QuestionViewService;

class Practice extends Component
{
    public $current, $selectedOption, $subjectId = '', $chapterId = '';

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
        $this->loadRandom();
    }

    public function updatedSubjectId(): void
    {
        $this->chapterId = '';
    }

    public function loadRandom()
    {
        $query = Question::with('options')
            ->when($this->subjectId, fn($q) => $q->where('subject_id', $this->subjectId))
            ->when($this->chapterId, fn($q) => $q->where('chapter_id', $this->chapterId));

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
        return view('livewire.practice', [
            'subjects' => Subject::orderBy('name')->get(),
            'chapters' => Chapter::when($this->subjectId, fn($q) => $q->where('subject_id', $this->subjectId))
                ->orderBy('name')
                ->get(),
        ])->layout(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.panel', ['title' => 'Practice']);
    }
}

