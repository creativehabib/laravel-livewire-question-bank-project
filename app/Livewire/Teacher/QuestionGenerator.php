<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use App\Models\{Subject, SubSubject, Chapter, Question};
use App\Support\Fonts;

class QuestionGenerator extends Component
{
    public string $examName = '';
    public ?int $subjectId = null;
    public ?int $subSubjectId = null;
    public ?int $chapterId = null;
    public string $questionType = 'mcq';
    public int $questionCount = 5;

    public string $programName = '';
    public string $classLevel = '';
    public string $setCode = '';
    public string $duration = '';
    public string $totalMarks = '';
    public string $instructionText = 'সরবরাহকৃত নৈর্ব্যত্তিক অভীক্ষার উত্তরপত্রে প্রশ্নের ক্রমিক নম্বরের বিপরীতে প্রদত্ত বর্ণসম্বলিত বৃত্ত সমূহ হতে সঠিক উত্তরের বৃত্তটি বল পয়েন্ট কলম দ্বারা সম্পূর্ণ ভরাট করো। প্রতিটি প্রশ্নের মান ১।';
    public string $noticeText = 'প্রশ্নপত্রে কোনো প্রকার দাগ/চিহ্ন দেয়া যাবেনা।';

    /**
     * Options to toggle sections within the preview layout.
     *
     * @var array<string, bool>
     */
    public array $previewOptions = [
        'attachAnswerSheet' => false,
        'attachOmrSheet' => false,
        'markImportant' => false,
        'showQuestionInfo' => true,
        'showSubSubject' => true,
        'showChapter' => true,
        'showSetCode' => true,
        'showStudentInfo' => false,
        'showMarksBox' => false,
        'showInstructions' => true,
        'showNotice' => true,
    ];

    public int $columnCount = 2;
    public string $textAlign = 'justify';
    public string $fontFamily = 'Bangla';
    public int $fontSize = 14;
    public string $optionStyle = 'circle';
    public string $paperSize = 'A4';

    /** @var array<int, array{id:int,name:string}> */
    public array $subSubjects = [];

    /** @var array<int, array{id:int,name:string}> */
    public array $chapters = [];

    /** @var array<int, array<string, mixed>> */
    public array $generatedQuestions = [];

    public string $sortOption = 'random';

    /** @var array<int> */
    public array $selectedQuestionIds = [];

    public ?array $questionPaperSummary = null;

    public bool $showGenerationResults = false;

    public bool $showPreview = false;

    public ?array $notification = null;

    protected array $rules = [
        'examName' => 'required|string|min:3',
        'subjectId' => 'required|exists:subjects,id',
        'subSubjectId' => 'nullable|exists:sub_subjects,id',
        'chapterId' => 'nullable|exists:chapters,id',
        'questionType' => 'required|string|in:mcq,creative,composite',
        'questionCount' => 'required|integer|min:1|max:50',
        'programName' => 'nullable|string|max:191',
        'classLevel' => 'nullable|string|max:191',
        'setCode' => 'nullable|string|max:50',
        'duration' => 'nullable|string|max:50',
        'totalMarks' => 'nullable|string|max:50',
        'instructionText' => 'nullable|string',
        'noticeText' => 'nullable|string',
        'sortOption' => 'nullable|string|in:random,newest,oldest,difficulty_high,difficulty_low',
    ];

    protected array $validationAttributes = [
        'examName' => 'পরীক্ষার নাম',
        'subjectId' => 'বিষয়',
        'subSubjectId' => 'সাব-বিষয়',
        'chapterId' => 'অধ্যায়',
        'questionType' => 'প্রশ্নের ধরন',
        'questionCount' => 'প্রশ্নের সংখ্যা',
        'selectedQuestionIds' => 'নির্বাচিত প্রশ্ন',
        'programName' => 'প্রোগ্রাম/প্রতিষ্ঠানের নাম',
        'classLevel' => 'শ্রেণি/লেভেল',
        'setCode' => 'সেট কোড',
        'duration' => 'সময়',
        'totalMarks' => 'পূর্ণমান',
        'instructionText' => 'নির্দেশনা',
        'noticeText' => 'ঘোষণা',
    ];

    public function mount(): void
    {
        if (! $this->programName && auth()->user()?->institution_name) {
            $this->programName = (string) auth()->user()->institution_name;
        }
    }

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

        $this->showPreview = false;

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

        $queryWithType = $this->applySorting($queryWithType);

        if (! empty($typeKeywords)) {
            $queryWithType->whereHas('tags', function ($tagQuery) use ($typeKeywords) {
                $tagQuery->where(function ($inner) use ($typeKeywords) {
                    foreach ($typeKeywords as $keyword) {
                        $inner->orWhere('name', 'like', "%{$keyword}%");
                    }
                });
            });
        }

        $questions = $queryWithType->take($this->questionCount * 2)->get();

        $usedFallback = false;
        if ($questions->isEmpty() && ! empty($typeKeywords)) {
            $fallbackQuery = $this->applySorting(clone $baseQuery);
            $questions = $fallbackQuery->take($this->questionCount * 2)->get();
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
                'created_at' => optional($question->created_at)->getTimestamp() ?? 0,
                'tags' => $question->tags->pluck('name')->all(),
            ])
            ->all();

        $this->sortGeneratedQuestions();
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

        $relations = [
            'chapter.subSubject',
            'subject',
        ];

        if ($this->questionType === 'mcq') {
            $relations['options'] = static fn ($query) => $query->orderBy('id');
        }

        $selectedQuestions = Question::query()
            ->with($relations)
            ->whereIn('id', $this->selectedQuestionIds)
            ->get();

        if ($selectedQuestions->isEmpty()) {
            $this->addError('selectedQuestionIds', __('নির্বাচিত প্রশ্নগুলো পাওয়া যায়নি।'));
            return;
        }

        $hasSubSubjects = ! empty($this->subSubjects);

        $this->questionPaperSummary = [
            'exam_name' => $this->examName,
            'program_name' => $this->programName ?: $this->examName,
            'subject' => optional($selectedQuestions->first()->subject)->name,
            'sub_subject' => $this->subSubjectId
                ? optional($selectedQuestions->first()->chapter?->subSubject)->name
                : ($hasSubSubjects ? __('বহু সাব-বিষয়') : __('সাব-বিষয় প্রযোজ্য নয়')),
            'chapter' => $this->chapterId ? optional($selectedQuestions->first()->chapter)->name : __('বহু অধ্যায়'),
            'type' => $this->questionTypeLabel($this->questionType),
            'type_key' => $this->questionType,
            'total_questions' => $selectedQuestions->count(),
            'class_level' => $this->classLevel,
            'duration' => $this->duration,
            'total_marks' => $this->totalMarks,
            'set_code' => $this->setCode,
            'instruction_text' => $this->instructionText,
            'notice_text' => $this->noticeText,
            'questions' => $selectedQuestions->map(fn (Question $question) => [
                'id' => $question->id,
                'title' => $question->title,
                'chapter' => optional($question->chapter)->name,
                'options' => $this->questionType === 'mcq'
                    ? $question->options
                        ->map(static fn ($option) => $option->option_text)
                        ->filter()
                        ->values()
                        ->all()
                    : [],
            ])->all(),
        ];

        $this->notification = [
            'type' => 'success',
            'message' => __('প্রশ্নপত্র সফলভাবে প্রস্তুত হয়েছে!'),
        ];

        $this->showPreview = false;
    }

    public function updatedSortOption(string $value): void
    {
        if (! array_key_exists($value, $this->sortOptions())) {
            $this->sortOption = 'random';
        }

        if (! empty($this->generatedQuestions)) {
            $this->sortGeneratedQuestions();
        }
    }

    public function setTextAlign(string $alignment): void
    {
        if (! in_array($alignment, ['left', 'center', 'right', 'justify'], true)) {
            return;
        }

        $this->textAlign = $alignment;
    }

    public function setColumnCount(int $count): void
    {
        if ($count < 1 || $count > 3) {
            return;
        }

        $this->columnCount = $count;
    }

    public function increaseFontSize(): void
    {
        $this->fontSize = min($this->fontSize + 1, 20);
    }

    public function decreaseFontSize(): void
    {
        $this->fontSize = max($this->fontSize - 1, 10);
    }

    public function setFontFamily(string $font): void
    {
        if (! in_array($font, $this->allowedFontFamilies(), true)) {
            return;
        }

        $this->fontFamily = $font;
    }

    public function setPaperSize(string $size): void
    {
        if (! in_array($size, ['A4', 'Letter', 'Legal', 'A5'], true)) {
            return;
        }

        $this->paperSize = $size;
    }

    public function setOptionStyle(string $style): void
    {
        if (! in_array($style, ['circle', 'dot', 'parentheses', 'minimal'], true)) {
            return;
        }

        $this->optionStyle = $style;
    }

    public function render()
    {
        return view('livewire.teacher.question-generator-copy', [
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
            'typeOptions' => $this->questionTypeOptions(),
            'subSubjects' => $this->subSubjects,
            'chapters' => $this->chapters,
            'sortOptions' => $this->sortOptions(),
            'fontOptions' => $this->fontFamilyOptions(),
        ])->layout('layouts.admin', ['title' => __('প্রশ্ন ক্রিয়েট')]);
    }

    /**
     * @return array<string, string>
     */
    protected function fontFamilyOptions(): array
    {
        return Fonts::options();
    }

    /**
     * @return array<int, string>
     */
    protected function allowedFontFamilies(): array
    {
        return Fonts::keys();
    }

    public function updatedFontFamily(string $value): void
    {
        if (! in_array($value, $this->allowedFontFamilies(), true)) {
            $this->fontFamily = 'Bangla';
        }
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

    /**
     * @param  Builder<Question>  $query
     * @return Builder<Question>
     */
    protected function applySorting(Builder $query): Builder
    {
        return match ($this->sortOption) {
            'newest' => $query->orderByDesc('created_at'),
            'oldest' => $query->orderBy('created_at'),
            'difficulty_high' => $query->orderByDesc('difficulty'),
            'difficulty_low' => $query->orderBy('difficulty'),
            default => $query->inRandomOrder(),
        };
    }

    protected function sortGeneratedQuestions(): void
    {
        $questions = $this->generatedQuestions;

        switch ($this->sortOption) {
            case 'newest':
                usort($questions, fn ($a, $b) => ($b['created_at'] ?? 0) <=> ($a['created_at'] ?? 0));
                break;
            case 'oldest':
                usort($questions, fn ($a, $b) => ($a['created_at'] ?? 0) <=> ($b['created_at'] ?? 0));
                break;
            case 'difficulty_high':
                usort($questions, fn ($a, $b) => ((float) ($b['difficulty'] ?? 0)) <=> ((float) ($a['difficulty'] ?? 0)));
                break;
            case 'difficulty_low':
                usort($questions, fn ($a, $b) => ((float) ($a['difficulty'] ?? 0)) <=> ((float) ($b['difficulty'] ?? 0)));
                break;
            default:
                shuffle($questions);
                break;
        }

        $this->generatedQuestions = array_values($questions);
    }

    /**
     * @return array<string, string>
     */
    protected function sortOptions(): array
    {
        return [
            'random' => __('র‌্যান্ডম'),
            'newest' => __('সর্বশেষ'),
            'oldest' => __('পুরনো'),
            'difficulty_high' => __('কঠিন থেকে সহজ'),
            'difficulty_low' => __('সহজ থেকে কঠিন'),
        ];
    }
}
