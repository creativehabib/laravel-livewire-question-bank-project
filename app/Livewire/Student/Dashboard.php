<?php

namespace App\Livewire\Student;

use App\Models\ExamResult;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $latest = ExamResult::where('user_id', $user->id)->latest()->first();

        $daily = ExamResult::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $weekly = ExamResult::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subWeeks(5)->startOfWeek())
            ->get()
            ->groupBy(fn ($result) => $result->created_at->copy()->startOfWeek()->format('Y-m-d'))
            ->sortKeys()
            ->map(function ($group, string $weekStart) {
                $weekStartDate = Carbon::parse($weekStart);

                return [
                    'week' => (int) $weekStartDate->format('oW'),
                    'total' => $group->count(),
                ];
            })
            ->values();

        return view('livewire.student.dashboard', [
            'latest' => $latest,
            'daily' => $daily,
            'weekly' => $weekly,
        ])->layout('layouts.admin', ['title' => 'Student Dashboard']);
    }
}
