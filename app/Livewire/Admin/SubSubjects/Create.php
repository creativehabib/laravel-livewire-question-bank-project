<?php

namespace App\Livewire\Admin\SubSubjects;

use Livewire\Component;
use App\Models\SubSubject;
use App\Models\Subject;

class Create extends Component
{
    public $subject_id;
    public $name;

    public function updatedSubjectId(): void
    {
        $this->reset('name');
    }

    public function save()
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|unique:sub_subjects,name,NULL,id,subject_id,' . $this->subject_id,
        ]);

        SubSubject::create([
            'subject_id' => $this->subject_id,
            'name' => $this->name,
        ]);

        return redirect()->route('admin.sub-subjects.index')
            ->with('success', 'Sub subject created.');
    }

    public function render()
    {
        return view('livewire.admin.sub-subjects.create', [
            'subjects' => Subject::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Create Sub Subject']);
    }
}
