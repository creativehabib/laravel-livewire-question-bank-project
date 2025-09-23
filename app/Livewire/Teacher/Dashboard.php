<?php

namespace App\Livewire\Teacher;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\{Question, QuestionSet, Subject};

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $questionCount = Question::where('user_id', $user->id)->count();
        $subjects = Subject::withCount(['questions' => fn ($q) => $q->where('user_id', $user->id)])
            ->orderBy('name')
            ->get();
        $questionSets = QuestionSet::withCount('questions')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $activityTimeline = $this->buildActivityTimeline($user->id);

        return view('livewire.teacher.dashboard', [
            'questionCount' => $questionCount,
            'questionSetCount' => $questionSets->count(),
            'subjects' => $subjects,
            'questionSets' => $questionSets,
            'activityTimeline' => $activityTimeline,
        ])->layout('layouts.admin', ['title' => 'Teacher Dashboard']);
    }

    public function deleteQuestionSet(string $questionSetId): void
    {
        $userId = auth()->id();

        $questionSet = QuestionSet::where('user_id', $userId)
            ->where('id', $questionSetId)
            ->firstOrFail();

        $questionSet->questions()->detach();
        $questionSet->delete();

        session()->flash('success', 'প্রশ্ন সেটটি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * Build the activity timeline (last six months) for questions and question sets.
     */
    protected function buildActivityTimeline(int $userId): Collection
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $monthlyQuestionTotals = Question::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn (Question $question) => $question->created_at->format('Y-m'))
            ->map->count();

        $monthlyQuestionSetTotals = QuestionSet::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn (QuestionSet $questionSet) => $questionSet->created_at->format('Y-m'))
            ->map->count();

        $timeline = collect();

        for ($cursor = $startDate->copy(); $cursor <= $endDate; $cursor->addMonth()) {
            $monthKey = $cursor->format('Y-m');

            $timeline->push([
                'label' => $cursor->translatedFormat('M Y'),
                'questions' => (int) ($monthlyQuestionTotals[$monthKey] ?? 0),
                'question_sets' => (int) ($monthlyQuestionSetTotals[$monthKey] ?? 0),
            ]);
        }

        return $timeline;
    }
}
