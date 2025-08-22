<?php

namespace App\Livewire\Teacher;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.teacher.dashboard')
            ->layout('layouts.panel', ['title' => 'Teacher Dashboard']);
    }
}
