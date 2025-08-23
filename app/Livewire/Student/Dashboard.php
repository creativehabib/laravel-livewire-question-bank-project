<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\ExamResult;
use Illuminate\Support\Facades\Schema;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $latest = null;
        $daily = collect();
        $weekly = collect();

        if (Schema::hasTable('exam_results')) {
            $latest = ExamResult::where('user_id', $user->id)->latest()->first();

            $daily = ExamResult::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $weekly = ExamResult::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subWeeks(5)->startOfWeek())
                ->selectRaw('YEARWEEK(created_at, 1) as week, COUNT(*) as total')
                ->groupBy('week')
                ->orderBy('week')
                ->get();
        }

        return view('livewire.student.dashboard', [
            'latest' => $latest,
            'daily' => $daily,
            'weekly' => $weekly,
        ])->layout('layouts.admin', ['title' => 'Student Dashboard']);
    }
}
