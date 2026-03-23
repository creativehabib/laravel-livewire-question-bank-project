<?php

namespace App\Livewire\Admin\Subjects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Modal Properties
    public $name = '';
    public $editId = null;

    protected $listeners = ['deleteSubjectConfirmed' => 'delete'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ক্রিয়েট বাটনে ক্লিক করলে ফর্ম রিসেট হবে
    public function openModal()
    {
        $this->reset(['name', 'editId']);
        $this->resetValidation();
    }

    // এডিট বাটনে ক্লিক করলে ডেটা লোড হবে
    public function edit($id)
    {
        $this->resetValidation();
        $subject = Subject::findOrFail($id);

        $this->editId = $subject->id;
        $this->name = $subject->name;

        // মডাল ওপেন করার ইভেন্ট
        $this->dispatch('open-modal');
    }

    // সেভ বা আপডেট করার মেথড
    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('subjects', 'name')->ignore($this->editId)
            ],
        ]);

        if ($this->editId) {
            // Update
            $subject = Subject::find($this->editId);
            $subject->update(['name' => $this->name]);
            $message = 'Subject updated successfully!';
        } else {
            // Create
            Subject::create(['name' => $this->name]);
            $message = 'Subject created successfully!';
        }

        $this->reset(['name', 'editId']);
        $this->dispatch('close-modal');
        $this->dispatch('subjectSaved', message: $message);
    }

    // ডিলিট করার মেথড
    public function delete($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $subject->delete();
            $this->resetPage();
            $this->dispatch('subjectDeleted', message: 'Subject deleted successfully.');
        }
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
