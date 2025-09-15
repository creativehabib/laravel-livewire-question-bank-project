<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\{Subject, Chapter, Question, ExamResult};

class Exam extends Component
{
    public $subjectId = '';
    public $chapterId = '';
    public $totalQuestions = 20;
    public $duration = 20; // minutes

    public $subjects = [];
    public $chapters = [];

    public $questions = [];
    public $selectedOptions = [];
    public $score = 0;

    public $examStarted = false;
    public $examFinished = false;
    public $timeLeft = 0; // seconds

    public function mount(): void
    {
        $this->subjects = Subject::orderBy('name')->get();
        $this->loadChapters();
    }

    protected function loadChapters(): void
    {
        $this->chapters = $this->subjectId
            ? Chapter::where('subject_id', $this->subjectId)->orderBy('name')->get()
            : Chapter::orderBy('name')->get();
    }

    public function updatedSubjectId(): void
    {
        $this->chapterId = '';
        $this->loadChapters();
    }

    public function startExam(): void
    {
        $query = Question::with('options');

        if ($this->subjectId) {
            $query->where('subject_id', $this->subjectId);
        }

        if ($this->chapterId) {
            $query->where('chapter_id', $this->chapterId);
        }

        $this->questions = $query->inRandomOrder()->take($this->totalQuestions)->get();
        $this->score = 0;
        $this->selectedOptions = [];
        $this->examStarted = true;
        $this->examFinished = false;
        $this->timeLeft = $this->duration * 60;
    }

    public function submitExam(): void
    {
        $this->score = 0;
        foreach ($this->questions as $question) {
            $selectedOptionId = $this->selectedOptions[$question->id] ?? null;
            if ($selectedOptionId) {
                $selected = $question->options->firstWhere('id', $selectedOptionId);
                if ($selected && $selected->is_correct) {
                    $this->score++;
                }
            }
        }
        $this->finishExam();
    }

    public function tick(): void
    {
        if ($this->examStarted && $this->timeLeft > 0) {
            $this->timeLeft--;
            if ($this->timeLeft === 0) {
                $this->submitExam();
            }
        }
    }

    protected function finishExam(): void
    {
        $this->examStarted = false;
        $this->examFinished = true;
        ExamResult::create([
            'user_id' => auth()->id(),
            'score'   => $this->score,
        ]);
    }

    public function resetExam(): void
    {
        $this->examStarted = false;
        $this->examFinished = false;
        $this->questions = [];
        $this->selectedOptions = [];
        $this->score = 0;
        $this->timeLeft = 0;
    }

    public function banglaNumber(int $number): string
    {
        $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace(range(0, 9), $banglaDigits, (string) $number);
    }

    public function optionLabel(int $index): string
    {
        $labels = ['ক', 'খ', 'গ', 'ঘ'];
        return $labels[$index] ?? '';
    }

    public function render()
    {
        return view('livewire.student.exam')
            ->layout('layouts.admin', ['title' => 'Exam']);
    }
}
