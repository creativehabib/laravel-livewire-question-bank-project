<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
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

        return view('livewire.teacher.dashboard', [
            'questionCount' => $questionCount,
            'questionSetCount' => $questionSets->count(),
            'subjects' => $subjects,
            'questionSets' => $questionSets,
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
}
