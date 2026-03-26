<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\QuestionSet;
use Illuminate\Http\Request;
use App\Support\Fonts;

class QuestionPaper extends Component
{
    public QuestionSet $questionSet;
    public $questions;

    // Header Info
    public $instituteName;
    public $subject;
    public $subSubject;
    public $topics;

    // Formatting & Layout Properties
    public string $fontFamily = 'Bangla';
    public int $fontSize = 14;
    public string $textAlign = 'justify';
    public int $columnCount = 2;
    public string $paperSize = 'A4';
    public string $optionStyle = 'circle';
    public string $setCode = 'ক';

    // Watermark Properties (ছবি অনুযায়ী)
    public int $watermarkOpacity = 20;
    public int $watermarkSize = 30;
    public string $watermarkText = 'অনলাইন ডিজিটাল স্কুল';

    public array $previewOptions = [
        'attachAnswerSheet' => false,
        'attachOmrSheet' => false,
        'markImportant' => false,
        'showQuestionInfo' => true,
        'showSubSubject' => true,
        'showTopic' => false,
        'showSetCode' => true,
        'showStudentInfo' => false,
        'showMarksBox' => false,
        'showInstructions' => true,
        'showNotice' => true,
        'showExamName' => false,
        'showColumnDivider' => true,
        'showWatermark' => false, // Watermark toggle
    ];

    public function mount(Request $request)
    {
        $qsetId = $request->query('qset');

        $this->questionSet = QuestionSet::with(['questions' => function ($query) {
            $query->orderBy('pivot_order', 'asc');
        }, 'user'])->findOrFail($qsetId);

        // প্রশ্নগুলো আলাদা ভ্যারিয়েবলে স্টোর করা হলো যাতে শাফল করলে UI রি-রেন্ডার হয়
        $this->questions = $this->questionSet->questions;

        $this->subject = $this->questionSet->getRelatedSubject();
        $this->subSubject = $this->questionSet->getRelatedSubSubject();
        $this->topics = $this->questionSet->getRelatedTopics();
        $this->instituteName = $this->questionSet->user->institution_name ?? 'প্রতিষ্ঠানের নাম';

        // ডিফল্ট ওয়াটারমার্ক টেক্সট
        if(empty($this->watermarkText)) {
            $this->watermarkText = $this->instituteName;
        }
    }

    // --- Customization Methods ---
    public function setTextAlign($align) { $this->textAlign = $align; }
    public function setColumnCount($count) { $this->columnCount = $count; }
    public function setPaperSize($size) { $this->paperSize = $size; }
    public function setOptionStyle($style) { $this->optionStyle = $style; }
    public function increaseFontSize() { if($this->fontSize < 24) $this->fontSize++; }
    public function decreaseFontSize() { if($this->fontSize > 10) $this->fontSize--; }

    // --- Shuffle & Set Code ---
    public function shuffleQuestions()
    {
        // প্রশ্নগুলো এলোমেলো (Shuffle)
        $this->questions = collect($this->questions)->shuffle();
        $this->setCode = collect(['ক','খ','গ','ঘ'])
            ->reject(fn($c) => $c === $this->setCode)
            ->random();
    }

    public function render()
    {
        return view('livewire.teacher.question-paper', [
            'fontOptions' => Fonts::options(),
        ])->layout('layouts.admin');
    }

    public function updatedFontFamily(string $value): void
    {
        if (! in_array($value, Fonts::keys(), true)) {
            $this->fontFamily = 'Bangla';
        }
    }
}
