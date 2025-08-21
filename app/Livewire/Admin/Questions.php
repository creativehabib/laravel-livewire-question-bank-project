<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Question;

class Questions extends Component
{
    use WithPagination;

    /**
     * Search term for filtering questions.
     *
     * @var string
     */
    public $search = '';

    /**
     * Refresh the component when a question is deleted.
     *
     * @var array
     */
    protected $listeners = ['questionDeleted' => '$refresh'];

    /**
     * Reset the pagination when the search term is updated.
     */
    public function updatingSearch(): void
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
            ->latest()
            ->paginate(10);

        return view('livewire.admin.questions', [
            'questions' => $questions,
        ])->layout('layouts.admin', ['title' => 'Manage Questions']);
    }
}

