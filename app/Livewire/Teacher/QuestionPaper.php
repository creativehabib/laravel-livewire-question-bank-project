<?php

namespace App\Livewire\Teacher;

use App\Models\QuestionSet;
use App\Support\Fonts;
use Illuminate\Http\Request;
use Livewire\Component;

class QuestionPaper extends Component
{
    public QuestionSet $questionSet;
    public $questions;
    public $subject;
    public $subSubject;
    public $chapters;

    public string $instituteName = 'প্রতিষ্ঠানের নাম';
    public string $fontFamily = 'Bangla';
    public int $fontSize = 14;
    public string $textAlign = 'justify';
    public int $columnCount = 2;
    public string $paperSize = 'A4';
    public string $optionStyle = 'circle';
    public string $setCode = 'ক';
    public int $watermarkOpacity = 20;
    public int $watermarkSize = 30;
    public string $watermarkText = 'অনলাইন ডিজিটাল স্কুল';

    public array $previewOptions = [
        'attachAnswerSheet' => false,
        'attachOmrSheet' => false,
        'markImportant' => false,
        'showQuestionInfo' => true,
        'showSubSubject' => true,
        'showChapter' => false,
        'showSetCode' => true,
        'showStudentInfo' => false,
        'showMarksBox' => false,
        'showInstructions' => true,
        'showNotice' => true,
        'showExamName' => false,
        'showColumnDivider' => true,
        'showWatermark' => false,
    ];

    protected array $allowedTextAlignments = ['left', 'center', 'right', 'justify'];
    protected array $allowedPaperSizes = ['A4', 'Letter', 'Legal', 'A5'];
    protected array $allowedOptionStyles = ['circle', 'dot', 'bracket', 'suffix'];
    protected array $allowedColumnCounts = [1, 2, 3];
    protected array $setCodes = ['ক', 'খ', 'গ', 'ঘ'];

    public function mount(Request $request): void
    {
        $qsetId = $request->query('qset');

        $this->questionSet = QuestionSet::with([
            'questions' => fn ($query) => $query->with('options')->orderBy('pivot_order', 'asc'),
            'user',
        ])->findOrFail($qsetId);

        $this->questions = $this->questionSet->questions->values();
        $this->subject = $this->questionSet->getRelatedSubject();
        $this->subSubject = $this->questionSet->getRelatedSubSubject();
        $this->chapters = $this->questionSet->getRelatedChapters();
        $this->instituteName = $this->questionSet->user->institution_name ?? 'প্রতিষ্ঠানের নাম';
        $this->watermarkText = $this->instituteName;
    }

    public function setTextAlign(string $align): void
    {
        if (in_array($align, $this->allowedTextAlignments, true)) {
            $this->textAlign = $align;
        }
    }

    public function setColumnCount(int $count): void
    {
        if (in_array($count, $this->allowedColumnCounts, true)) {
            $this->columnCount = $count;
        }
    }

    public function setPaperSize(string $size): void
    {
        if (in_array($size, $this->allowedPaperSizes, true)) {
            $this->paperSize = $size;
        }
    }

    public function setOptionStyle(string $style): void
    {
        if (in_array($style, $this->allowedOptionStyles, true)) {
            $this->optionStyle = $style;
        }
    }

    public function increaseFontSize(): void
    {
        if ($this->fontSize < 24) {
            $this->fontSize++;
        }
    }

    public function decreaseFontSize(): void
    {
        if ($this->fontSize > 10) {
            $this->fontSize--;
        }
    }

    public function shuffleQuestions(): void
    {
        $this->questions = collect($this->questions)->shuffle()->values();
        $this->setCode = $this->setCodes[array_rand($this->setCodes)];
    }

    public function updatedFontFamily(string $value): void
    {
        if (! in_array($value, Fonts::keys(), true)) {
            $this->fontFamily = 'Bangla';
        }
    }

    public function updatedFontSize(int $value): void
    {
        $this->fontSize = max(10, min(24, $value));
    }

    public function updatedWatermarkOpacity(int $value): void
    {
        $this->watermarkOpacity = max(5, min(60, $value));
    }

    public function updatedWatermarkSize(int $value): void
    {
        $this->watermarkSize = max(16, min(72, $value));
    }

    public function render()
    {
        return view('livewire.teacher.question-paper', [
            'fontOptions' => Fonts::options(),
            'questions' => collect($this->questions),
        ])->layout('layouts.admin');
    }
}
