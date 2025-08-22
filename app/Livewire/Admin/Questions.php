<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{Question, Subject, Chapter};

class Questions extends Component
{
    use WithPagination;

    /**
     * Search term for filtering questions.
     */
    public $search = '';

    /**
     * Selected subject filter.
     */
    public $subjectId = '';

    /**
     * Selected chapter filter.
     */
    public $chapterId = '';

    /**
     * Refresh the component when a question is deleted.
     *
     * @var array
     */
    protected $listeners = [
        'questionDeleted' => '$refresh',
        'deleteQuestionConfirmed' => 'deleteQuestion',
    ];

    /**
     * Reset the pagination when the search term is updated.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSubjectId(): void
    {
        $this->resetPage();
        $this->chapterId = '';
    }

    public function updatingChapterId(): void
    {
        $this->resetPage();
    }

    /**
     * Permanently delete a question along with its relations.
     */
    public function deleteQuestion(int $id): void
    {
        $question = Question::with(['tags', 'options'])->findOrFail($id);

        $question->tags()->detach();
        $question->options()->delete();

        $question->forceDelete();

        $this->dispatch('questionDeleted', message: 'Question deleted successfully.');
        $this->resetPage();
    }

    public function render()
    {
        $questions = Question::with('subject', 'chapter')
            ->when($this->search, function ($q) {
                $search = '%' . $this->search . '%';
                $q->where('title', 'like', $search)
                    ->orWhereRelation('subject', 'name', 'like', $search)
                    ->orWhereRelation('chapter', 'name', 'like', $search);
            })
            ->when($this->subjectId, fn($q) => $q->where('subject_id', $this->subjectId))
            ->when($this->chapterId, fn($q) => $q->where('chapter_id', $this->chapterId))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.questions', [
            'questions' => $questions,
            'subjects' => Subject::orderBy('name')->get(),
            'chapters' => Chapter::when($this->subjectId, fn($q) => $q->where('subject_id', $this->subjectId))
                ->orderBy('name')
                ->get(),
        ])->layout('layouts.admin', ['title' => 'Manage Questions']);
    }
}

