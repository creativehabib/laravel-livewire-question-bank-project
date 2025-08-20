<?php

namespace App\Livewire\Admin;

use App\Models\Subject;
use App\Models\Question;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'subjectsCount' => Subject::count(),
            'questionsCount' => Question::count(),
            'usersCount' => User::count(),
            'newQuestionsToday' => Question::whereDate('created_at', today())->count(),
            'recentQuestions' => Question::with('subject')->latest()->take(5)->get(),
            'subjectChartData' => Subject::withCount('questions')->get(['name', 'questions_count']),
            'messageCounts' => ChatMessage::selectRaw("DATE(created_at) as date, COUNT(*) as count")
                ->where('created_at', '>=', now()->subDays(6))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date')
                ->toArray(),
            'viewChartData' => Question::orderByDesc('views')->take(5)
                ->get(['title', 'views'])
                ->map(fn($q) => [
                    'title' => Str::limit(strip_tags($q->title), 20),
                    'views' => $q->views,
                ]),
        ])->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
