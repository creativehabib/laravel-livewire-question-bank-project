<?php

namespace App\Livewire\Admin\Chapters;

use Livewire\Component;
use App\Models\Chapter;
use App\Models\Subject;

class Edit extends Component
{
    public Chapter $chapter;
    public $subject_id;
    public $name;

    public function mount(Chapter $chapter)
    {
        $this->chapter = $chapter;
        $this->subject_id = $chapter->subject_id;
        $this->name = $chapter->name;
    }

    public function update()
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|unique:chapters,name,' . $this->chapter->id . ',id,subject_id,' . $this->subject_id,
        ]);

        $this->chapter->update([
            'subject_id' => $this->subject_id,
            'name' => $this->name,
        ]);

        return redirect()->route('admin.chapters.index')
            ->with('success', 'Chapter updated.');
    }

    public function render()
    {
        return view('livewire.admin.chapters.edit', [
            'subjects' => Subject::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Edit Chapter']);
    }
}
