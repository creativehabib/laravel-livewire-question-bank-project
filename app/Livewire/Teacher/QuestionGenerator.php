<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\{Subject, SubSubject, Chapter, Question};

class QuestionGenerator extends Component
{
    public string $examName = '';
    public ?int $subjectId = null;
    public ?int $subSubjectId = null;
    public ?int $chapterId = null;
    public string $questionType = 'mcq';
    public int $questionCount = 5;

    /** @var array<int, array{id:int,name:string}> */
    public array $subSubjects = [];

    /** @var array<int, array{id:int,name:string}> */
    public array $chapters = [];

    /** @var array<int, array<string, mixed>> */
    public array $generatedQuestions = [];

    /** @var array<int> */
    public array $selectedQuestionIds = [];

    public ?array $questionPaperSummary = null;

    public bool $showGenerationResults = false;

    public ?array $notification = null;

    protected array $rules = [
        'examName' => 'required|string|min:3',
        'subjectId' => 'required|exists:subjects,id',
        'subSubjectId' => 'nullable|exists:sub_subjects,id',
        'chapterId' => 'nullable|exists:chapters,id',
        'questionType' => 'required|string|in:mcq,creative,composite',
        'questionCount' => 'required|integer|min:1|max:50',
    ];

    protected array $validationAttributes = [
        'examName' => 'পরীক্ষার নাম',
        'subjectId' => 'বিষয়',
        'subSubjectId' => 'সাব-বিষয়',
        'chapterId' => 'অধ্যায়',
        'questionType' => 'প্রশ্নের ধরন',
        'questionCount' => 'প্রশ্নের সংখ্যা',
        'selectedQuestionIds' => 'নির্বাচিত প্রশ্ন',
    ];

    public function updatedSubjectId($value): void
    {
        $this->chapterId = null;
        $this->subSubjectId = null;
        $this->subSubjects = $value
            ? SubSubject::query()
                ->where('subject_id', $value)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (SubSubject $subSubject) => ['id' => $subSubject->id, 'name' => $subSubject->name])
                ->all()
            : [];

        $this->chapters = [];

        if ($value && empty($this->subSubjects)) {
            $this->chapters = Chapter::query()
                ->where('subject_id', $value)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Chapter $chapter) => ['id' => $chapter->id, 'name' => $chapter->name])
                ->all();
        }
    }

    public function updatedSubSubjectId($value): void
    {
        $this->chapterId = null;

        if (! $value) {
            $this->chapters = [];
            return;
        }

        $this->chapters = Chapter::query()
            ->where('sub_subject_id', $value)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Chapter $chapter) => ['id' => $chapter->id, 'name' => $chapter->name])
            ->all();
    }

    public function updatedChapterId($value): void
    {
        if (! $value) {
            return;
        }

        if ($this->subSubjectId && ! Chapter::where('id', $value)->where('sub_subject_id', $this->subSubjectId)->exists()) {
            $this->addError('chapterId', __('নির্বাচিত অধ্যায় এই সাব-বিষয়ের অন্তর্ভুক্ত নয়।'));
            $this->chapterId = null;
            return;
        }

        if ($this->subjectId && ! Chapter::where('id', $value)->where('subject_id', $this->subjectId)->exists()) {
            $this->addError('chapterId', __('নির্বাচিত অধ্যায় এই বিষয়ের অন্তর্ভুক্ত নয়।'));
            $this->chapterId = null;
        }
    }

    public function generateQuestions(): void
    {
        $this->validate();

        $baseQuery = Question::query()
            ->with(['chapter.subSubject', 'subject', 'tags'])
            ->where('subject_id', $this->subjectId);

        if ($this->subSubjectId) {
            $baseQuery->where('sub_subject_id', $this->subSubjectId);
        }

        if ($this->chapterId) {
            $baseQuery->where('chapter_id', $this->chapterId);
        }

        $typeKeywords = $this->questionTypeKeywords($this->questionType);
        $queryWithType = clone $baseQuery;

        if (! empty($typeKeywords)) {
            $queryWithType->whereHas('tags', function ($tagQuery) use ($typeKeywords) {
                $tagQuery->where(function ($inner) use ($typeKeywords) {
                    foreach ($typeKeywords as $keyword) {
                        $inner->orWhere('name', 'like', "%{$keyword}%");
                    }
                });
            });
        }

        $questions = $queryWithType->inRandomOrder()->take($this->questionCount * 2)->get();

        $usedFallback = false;
        if ($questions->isEmpty() && ! empty($typeKeywords)) {
            $questions = (clone $baseQuery)->inRandomOrder()->take($this->questionCount * 2)->get();
            $usedFallback = $questions->isNotEmpty();
        }

        if ($questions->isEmpty()) {
            $this->generatedQuestions = [];
            $this->showGenerationResults = false;
            $this->notification = [
                'type' => 'warning',
                'message' => __('নির্বাচিত সেটিংস অনুযায়ী কোনো প্রশ্ন পাওয়া যায়নি।'),
            ];
            return;
        }

        $this->generatedQuestions = $questions
            ->take($this->questionCount)
            ->map(fn (Question $question) => [
                'id' => $question->id,
                'title' => $question->title,
                'chapter' => optional($question->chapter)->name,
                'subject' => optional($question->subject)->name,
                'difficulty' => $question->difficulty,
                'tags' => $question->tags->pluck('name')->all(),
            ])
            ->all();

        $this->selectedQuestionIds = [];
        $this->showGenerationResults = true;
        $this->questionPaperSummary = null;
        $this->notification = $usedFallback
            ? [
                'type' => 'warning',
                'message' => __('নির্বাচিত প্রশ্নের ধরন অনুযায়ী প্রশ্ন পাওয়া যায়নি, সাধারণ প্রশ্নগুলো দেখানো হচ্ছে।'),
            ]
            : null;
    }

    public function saveSelection(): void
    {
        $this->validate([
            'selectedQuestionIds' => 'required|array|min:1',
        ], [
            'selectedQuestionIds.required' => __('কমপক্ষে একটি প্রশ্ন নির্বাচন করুন।'),
            'selectedQuestionIds.min' => __('কমপক্ষে একটি প্রশ্ন নির্বাচন করুন।'),
        ]);

        $selectedQuestions = Question::query()
            ->with(['chapter.subSubject', 'subject'])
            ->whereIn('id', $this->selectedQuestionIds)
            ->get();

        if ($selectedQuestions->isEmpty()) {
            $this->addError('selectedQuestionIds', __('নির্বাচিত প্রশ্নগুলো পাওয়া যায়নি।'));
            return;
        }

        $hasSubSubjects = ! empty($this->subSubjects);

        $this->questionPaperSummary = [
            'exam_name' => $this->examName,
            'subject' => optional($selectedQuestions->first()->subject)->name,
            'sub_subject' => $this->subSubjectId
                ? optional($selectedQuestions->first()->chapter?->subSubject)->name
                : ($hasSubSubjects ? __('বহু সাব-বিষয়') : __('সাব-বিষয় প্রযোজ্য নয়')),
            'chapter' => $this->chapterId ? optional($selectedQuestions->first()->chapter)->name : __('বহু অধ্যায়'),
            'type' => $this->questionTypeLabel($this->questionType),
            'total_questions' => $selectedQuestions->count(),
            'questions' => $selectedQuestions->map(fn (Question $question) => [
                'id' => $question->id,
                'title' => $question->title,
                'chapter' => optional($question->chapter)->name,
            ])->all(),
        ];

        $this->notification = [
            'type' => 'success',
            'message' => __('প্রশ্নপত্র সফলভাবে প্রস্তুত হয়েছে!'),
        ];
    }

    public function render()
    {
        return view('livewire.teacher.question-generator', [
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
            'typeOptions' => $this->questionTypeOptions(),
            'subSubjects' => $this->subSubjects,
            'chapters' => $this->chapters,
        ])->layout('layouts.admin', ['title' => __('প্রশ্ন ক্রিয়েট')]);
    }

    /**
     * @return array<string, string>
     */
    protected function questionTypeOptions(): array
    {
        return [
            'mcq' => __('এমসিকিউ'),
            'creative' => __('সৃজনশীল'),
            'composite' => __('সংমিশ্রন'),
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function questionTypeKeywords(string $type): array
    {
        return match ($type) {
            'mcq' => ['mcq', 'multiple', 'choice', 'এমসিকিউ'],
            'creative' => ['creative', 'সৃজনশীল'],
            'composite' => ['composite', 'সংমিশ্রন', 'সংমিশ্রণ'],
            default => [],
        };
    }

    protected function questionTypeLabel(string $type): string
    {
        return $this->questionTypeOptions()[$type] ?? $type;
    }
}
