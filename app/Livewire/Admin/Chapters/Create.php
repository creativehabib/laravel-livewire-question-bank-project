<?php

namespace App\Livewire\Admin\Chapters;

use Livewire\Component;
use App\Models\Chapter;
use App\Models\Subject;

class Create extends Component
{
    public $subject_id;
    public $name;

    public function save()
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|unique:chapters,name,NULL,id,subject_id,' . $this->subject_id,
        ]);

        Chapter::create([
            'subject_id' => $this->subject_id,
            'name' => $this->name,
        ]);

        return redirect()->route('admin.chapters.index')
            ->with('success', 'Chapter created.');
    }

    public function render()
    {
        return view('livewire.admin.chapters.create', [
            'subjects' => Subject::orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Create Chapter']);
    }
}
