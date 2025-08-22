<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\{Question, Subject};

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $questionCount = Question::where('user_id', $user->id)->count();
        $subjects = Subject::withCount(['questions' => fn($q) => $q->where('user_id', $user->id)])
            ->orderBy('name')
            ->get();

        return view('livewire.teacher.dashboard', [
            'questionCount' => $questionCount,
            'subjects' => $subjects,
        ])->layout('layouts.admin', ['title' => 'Teacher Dashboard']);
    }
}
