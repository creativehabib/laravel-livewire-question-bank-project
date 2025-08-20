<?php

namespace App\Livewire\Admin\Subjects;

use Livewire\Component;
use App\Models\Subject;

class Create extends Component
{
    public $name;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:subjects,name',
        ]);

        Subject::create(['name' => $this->name]);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created.');
    }

    public function render()
    {
        return view('livewire.admin.subjects.create')
            ->layout('layouts.app', ['title' => 'Create Subject']);
    }
}
