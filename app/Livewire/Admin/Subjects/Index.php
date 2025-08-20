<?php

namespace App\Livewire\Admin\Subjects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = ['subjectDeleted' => '$refresh'];

    public function delete($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        $this->dispatch('subjectDeleted');
        session()->flash('success', 'Subject deleted successfully.');
    }

    public function render()
    {
        $subjects = Subject::when($this->search, fn($q) =>
            $q->where('name', 'like', '%'.$this->search.'%')
        )->orderBy('name')->paginate(10);

        return view('livewire.admin.subjects.index', [
            'subjects' => $subjects,
        ])->layout('layouts.app', ['title' => 'Manage Subjects']);
    }
}
