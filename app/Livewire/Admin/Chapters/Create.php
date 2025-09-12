<?php

namespace App\Livewire\Admin\Chapters;

use Livewire\Component;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\SubSubject;

class Create extends Component
{
    public $subject_id;
    public $sub_subject_id;
    public $name;

    public function updatedSubjectId($value): void
    {
        $this->sub_subject_id = null;

        $subSubjects = SubSubject::where('subject_id', $value)
            ->get()
            ->map(fn($s) => ['value' => $s->id, 'text' => $s->name])
            ->all();

        $this->dispatch('subSubjectsUpdated', subSubjects: $subSubjects);
    }

    public function save()
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'sub_subject_id' => 'required|exists:sub_subjects,id',
            'name' => 'required|string|unique:chapters,name,NULL,id,subject_id,' . $this->subject_id . ',sub_subject_id,' . $this->sub_subject_id,
        ]);

        Chapter::create([
            'subject_id' => $this->subject_id,
            'sub_subject_id' => $this->sub_subject_id,
            'name' => $this->name,
        ]);

        return redirect()->route('admin.chapters.index')
            ->with('success', 'Chapter created.');
    }

    public function render()
    {
        return view('livewire.admin.chapters.create', [
            'subjects' => Subject::orderBy('name')->get(),
            'subSubjects' => SubSubject::where('subject_id', $this->subject_id)->orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Create Chapter']);
    }
}
