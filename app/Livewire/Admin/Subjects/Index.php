<?php

namespace App\Livewire\Admin\Subjects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = [
        'subjectDeleted' => '$refresh',
        'deleteSubjectConfirmed' => 'delete',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Subject::findOrFail($id)->delete();

        $this->resetPage();
        $this->dispatch('subjectDeleted', message: 'Subject deleted successfully.');
    }

    public function render()
    {
        $subjects = Subject::when($this->search, fn($q) =>
            $q->where('name', 'like', '%'.$this->search.'%')
        )->orderBy('name')->paginate(10);

        return view('livewire.admin.subjects.index', [
            'subjects' => $subjects,
        ])->layout('layouts.admin', ['title' => 'Manage Subjects']);
    }
}
