<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\{Subject, SubSubject, Chapter, Question, Tag};
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use AuthorizesRequests;

    public $subject_id, $sub_subject_id, $chapter_id, $title, $description, $difficulty = 'easy', $question_type = 'mcq', $marks = 1, $tagIds = [];
    public $options = [];
    public $cq = []; // CQ এর জন্য প্রপার্টি

    public function mount(): void
    {
        $this->resetFields();
    }

    public function resetFields(): void
    {
        $this->reset('subject_id', 'sub_subject_id', 'chapter_id', 'title', 'description', 'difficulty', 'question_type', 'marks', 'tagIds', 'options', 'cq');
        $this->difficulty = 'easy';
        $this->question_type = 'mcq';
        $this->marks = 1;
        $this->resetToMcq();
        $this->setCqDefaults(); // ব্যাকগ্রাউন্ডে CQ রেডি রাখা
        $this->dispatch('reset-selects');
    }

    private function resetToMcq(): void
    {
        $this->options = [
            ['option_text' => '', 'is_correct' => false],
            ['option_text' => '', 'is_correct' => false],
            ['option_text' => '', 'is_correct' => false],
            ['option_text' => '', 'is_correct' => false],
        ];
    }

    private function setCqDefaults(): void
    {
        $this->cq = [
            ['id' => uniqid(), 'label' => 'ক', 'text' => '', 'marks' => 1],
            ['id' => uniqid(), 'label' => 'খ', 'text' => '', 'marks' => 2],
            ['id' => uniqid(), 'label' => 'গ', 'text' => '', 'marks' => 3],
            ['id' => uniqid(), 'label' => 'ঘ', 'text' => '', 'marks' => 4],
        ];
    }

    public function addCqPart(): void
    {
        $labels = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ', 'ঝ', 'ঞ'];
        $nextLabel = $labels[count($this->cq)] ?? '*';

        $this->cq[] = ['id' => uniqid(), 'label' => $nextLabel, 'text' => '', 'marks' => 1];
        $this->calculateCqMarks();
        $this->dispatch('refresh-editors');
    }

    public function removeCqPart($index): void
    {
        unset($this->cq[$index]);
        $this->cq = array_values($this->cq);
        $this->calculateCqMarks();
    }

    public function calculateCqMarks(): void
    {
        $this->marks = array_sum(array_column($this->cq, 'marks'));
    }

    public function updated($property, $value): void
    {
        if ($this->question_type === 'cq' && str_starts_with($property, 'cq.') && str_ends_with($property, '.marks')) {
            $this->calculateCqMarks();
        }
    }

    public function updatedQuestionType($value): void
    {
        if ($value === 'mcq') {
            $this->marks = 1;
            if (empty($this->options)) $this->resetToMcq();
        } elseif ($value === 'cq') {
            if (empty($this->cq)) $this->setCqDefaults();
            $this->calculateCqMarks();
            $this->options = [];
        } else {
            $this->marks = 2; // Short Question
            $this->options = [];
        }
        $this->dispatch('refresh-editors');
    }

    public function updatedSubjectId($value)
    {
        $this->sub_subject_id = null;
        $this->chapter_id = null;
        $subSubjects = SubSubject::where('subject_id', $value)->get()->map(fn($s) => ['value' => $s->id, 'text' => $s->name])->all();
        $this->dispatch('subSubjectsUpdated', subSubjects: $subSubjects);

        $this->dispatch('chaptersUpdated', chapters: []);
    }

    public function updatedSubSubjectId($value)
    {
        $this->chapter_id = null;
        $chapters = $value ? Chapter::where('sub_subject_id', $value)->get()->map(fn($c) => ['value' => $c->id, 'text' => $c->name])->all() : [];
        $this->dispatch('chaptersUpdated', chapters: $chapters);
    }

    public function save()
    {
        $this->authorize('create', Question::class);

        $rules = [
            'subject_id' => 'required|exists:subjects,id',
            'sub_subject_id' => 'nullable|exists:sub_subjects,id',
            'chapter_id' => 'required_with:sub_subject_id|nullable|exists:chapters,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_type' => 'required|in:mcq,cq,short',
            'marks' => 'required|numeric|min:0',
            'tagIds' => 'nullable|array',
        ];

        if ($this->question_type === 'mcq') {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.option_text'] = 'required|string';
        }

        $this->validate($rules);

        DB::transaction(function () {

            // প্রশ্ন টাইপের উপর ভিত্তি করে extra_content এ কী সেভ হবে তা নির্ধারণ করা হলো
            $extraData = null;
            if ($this->question_type === 'cq') {
                $extraData = $this->cq;
            } elseif ($this->question_type === 'mcq') {
                $extraData = $this->options;
            }

            $question = Question::create([
                'subject_id' => $this->subject_id,
                'sub_subject_id' => $this->sub_subject_id ?: null,
                'chapter_id' => $this->chapter_id ?: null,
                'title' => $this->title,
                'description' => $this->description,
                'difficulty' => $this->difficulty,
                'question_type' => $this->question_type,
                'marks' => $this->marks,
                'extra_content' => $extraData, // MCQ এবং CQ উভয় অপশনই এখন JSON হিসেবে এখানে সেভ হবে
                'user_id' => auth()->id(),
            ]);

            if ($this->tagIds) {
                $tagIds = collect($this->tagIds)->map(fn($tag) => is_numeric($tag) ? (int) $tag : Tag::firstOrCreate(['name' => $tag])->id)->toArray();
                $question->tags()->sync($tagIds);
            }

            // আগের $question->options()->createMany(...) অংশটি মুছে ফেলা হয়েছে
        });

        $route = auth()->user()->isTeacher() ? 'teacher.questions.index' : 'admin.questions.index';
        return redirect()->route($route)->with('success', 'Question created successfully.');
    }

    public function render()
    {
        $layout = auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.panel';
        return view('livewire.admin.questions.create', [
            'subjects' => Subject::all(),
            'subSubjects' => SubSubject::where('subject_id', $this->subject_id)->get(),
            'chapters' => Chapter::where('sub_subject_id', $this->sub_subject_id)->get(),
            'allTags' => Tag::all(),
        ])->layout($layout, ['title' => 'Create Question']);
    }
}
