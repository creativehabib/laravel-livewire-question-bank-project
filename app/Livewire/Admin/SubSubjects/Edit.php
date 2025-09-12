<?php

namespace App\Livewire\Admin\SubSubjects;

use Livewire\Component;
use App\Models\SubSubject;
use App\Models\Subject;

class Edit extends Component
{
    public SubSubject $subSubject;
    public $subject_id;
    public $name;

    public function mount(SubSubject $subSubject)
    {
        $this->subSubject = $subSubject;
        $this->subject_id = $subSubject->subject_id;
        $this->name = $subSubject->name;
    }

    public function updatedSubjectId(): void
    {
        $this->reset('name');
    }

    public function update()
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|unique:sub_subjects,name,' . $this->subSubject->id . ',id,subject_id,' . $this->subject_id,
        ]);

        $this->subSubject->update([
            'subject_id' => $this->subject_id,
            'name' => $this->name,
        ]);

        return redirect()->route('admin.sub-subjects.index')
            ->with('success', 'Sub subject updated.');
    }

    public function render()
    {
        return view('livewire.admin.sub-subjects.edit', [
            'subjects' => Subject::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Edit Sub Subject']);
    }
}
