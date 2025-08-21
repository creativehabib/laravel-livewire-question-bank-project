<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Question;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = ['questionDeleted' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteQuestion($id)
    {
        $question = Question::with('tags')->findOrFail($id);

        // remove related data first
        $question->tags()->detach();
        $question->options()->delete();

        // force delete to remove the soft deleted record completely
        $question->forceDelete();

        $this->dispatch('questionDeleted');
        session()->flash('success', 'Question deleted successfully.');
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

        return view('livewire.admin.questions.index', [
            'questions' => $questions
        ])->layout('layouts.admin', ['title' => 'Manage Questions']);
    }
}

