<?php

namespace App\Livewire\Admin\Subjects;

use Livewire\Component;
use App\Models\Subject;

class Edit extends Component
{
    public Subject $subject;
    public $name;

    public function mount(Subject $subject)
    {
        $this->subject = $subject;
        $this->name = $subject->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|unique:subjects,name,' . $this->subject->id,
        ]);

        $this->subject->update(['name' => $this->name]);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated.');
    }

    public function render()
    {
        return view('livewire.admin.subjects.edit')
            ->layout('layouts.app', ['title' => 'Edit Subject']);
    }
}
