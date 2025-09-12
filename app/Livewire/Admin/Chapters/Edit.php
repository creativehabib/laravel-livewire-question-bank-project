<?php

namespace App\Livewire\Admin\Chapters;

use Livewire\Component;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\SubSubject;

class Edit extends Component
{
    public Chapter $chapter;
    public $subject_id;
    public $sub_subject_id;
    public $name;

    public function mount(Chapter $chapter)
    {
        $this->chapter = $chapter;
        $this->subject_id = $chapter->subject_id;
        $this->sub_subject_id = $chapter->sub_subject_id;
        $this->name = $chapter->name;
    }

    public function updatedSubjectId(): void
    {
        $this->sub_subject_id = null;
    }

    public function update()
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'sub_subject_id' => 'required|exists:sub_subjects,id',
            'name' => 'required|string|unique:chapters,name,' . $this->chapter->id . ',id,subject_id,' . $this->subject_id . ',sub_subject_id,' . $this->sub_subject_id,
        ]);

        $this->chapter->update([
            'subject_id' => $this->subject_id,
            'sub_subject_id' => $this->sub_subject_id,
            'name' => $this->name,
        ]);

        return redirect()->route('admin.chapters.index')
            ->with('success', 'Chapter updated.');
    }

    public function render()
    {
        return view('livewire.admin.chapters.edit', [
            'subjects' => Subject::orderBy('name')->get(),
            'subSubjects' => SubSubject::where('subject_id', $this->subject_id)->orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Edit Chapter']);
    }
}
