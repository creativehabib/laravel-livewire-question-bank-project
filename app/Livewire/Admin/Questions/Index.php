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

    public function delete($id)
    {
        $question = Question::findOrFail($id);
        $question->options()->delete(); // child delete
        $question->delete();

        $this->dispatch('questionDeleted');
        session()->flash('success', 'Question deleted successfully.');
        $this->resetPage();
    }


    public function render()
    {
        $questions = Question::with('subject', 'chapter')
            ->when($this->search, function ($q) {
                $search = $this->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhereHas('subject', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('chapter', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.questions.index', [
            'questions' => $questions
        ])->layout('layouts.admin', ['title' => 'Manage Questions']);
    }
}

