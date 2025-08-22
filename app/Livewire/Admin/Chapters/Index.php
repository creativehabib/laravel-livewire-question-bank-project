<?php

namespace App\Livewire\Admin\Chapters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Chapter;
use App\Models\Subject;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $subjectId = '';

    protected $listeners = [
        'chapterDeleted' => '$refresh',
        'deleteChapterConfirmed' => 'delete',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSubjectId(): void
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Chapter::findOrFail($id)->delete();

        $this->resetPage();
        $this->dispatch('chapterDeleted', message: 'Chapter deleted successfully.');
    }

    public function render()
    {
        $chapters = Chapter::with('subject')
            ->when($this->subjectId, fn($q) => $q->where('subject_id', $this->subjectId))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.chapters.index', [
            'chapters' => $chapters,
            'subjects' => Subject::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Manage Chapters']);
    }
}
