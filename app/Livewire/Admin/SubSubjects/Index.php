<?php

namespace App\Livewire\Admin\SubSubjects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SubSubject;
use App\Models\Subject;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $subjectId = '';

    protected $listeners = [
        'subSubjectDeleted' => '$refresh',
        'deleteSubSubjectConfirmed' => 'delete',
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
        SubSubject::findOrFail($id)->delete();

        $this->resetPage();
        $this->dispatch('subSubjectDeleted', message: 'Sub subject deleted successfully.');
    }

    public function render()
    {
        $subSubjects = SubSubject::with('subject')
            ->when($this->subjectId, fn($q) => $q->where('subject_id', $this->subjectId))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.sub-subjects.index', [
            'subSubjects' => $subSubjects,
            'subjects' => Subject::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Manage Sub Subjects']);
    }
}
