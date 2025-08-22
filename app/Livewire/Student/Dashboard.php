<?php

namespace App\Livewire\Student;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.student.dashboard')
            ->layout('layouts.panel', ['title' => 'Student Dashboard']);
    }
}
