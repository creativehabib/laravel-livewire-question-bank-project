<?php

namespace App\Livewire\Admin\Questions;

use App\Livewire\Traits\SlugValidationTrait;
use Livewire\Component;
use Livewire\WithFileUploads; // Image Upload এর জন্য
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\{Subject, SubSubject, Topic, Question, Tag, ExamCategory};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Create extends Component
{
    use AuthorizesRequests, SlugValidationTrait, WithFileUploads; // WithFileUploads যুক্ত করা হলো

    public $subject_id, $sub_subject_id, $topic_id, $title, $description, $difficulty = 'easy', $question_type = 'mcq', $marks = 1, $tagIds = [];
    public $options = [];
    public $cq = [];
    public $slug;
    public $exam_category_ids = []; // Target Audience
    public $image; // Written প্রশ্নের ছবির জন্য

    public function mount(): void
    {
        $this->resetFields();
    }

    public function resetFields(): void
    {
        $this->reset('subject_id', 'sub_subject_id', 'topic_id', 'title', 'description', 'difficulty', 'question_type', 'marks', 'tagIds', 'options', 'cq', 'slug', 'exam_category_ids', 'image');
        $this->difficulty = 'easy';
        $this->question_type = 'mcq';
        $this->marks = 1;
        $this->resetToMcq();
        $this->setCqDefaults();
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

    // --- MCQ Options Methods ---
    public function addOption(): void
    {
        $this->options[] = ['option_text' => '', 'is_correct' => false];
        $this->dispatch('refresh-editors');
    }

    public function removeOption($index): void
    {
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    // --- CQ Methods ---
    private function setCqDefaults(): void
    {
        $this->cq = [
            ['id' => uniqid(), 'label' => 'ক', 'text' => '', 'answer' => '', 'marks' => 1],
            ['id' => uniqid(), 'label' => 'খ', 'text' => '', 'answer' => '', 'marks' => 2],
            ['id' => uniqid(), 'label' => 'গ', 'text' => '', 'answer' => '', 'marks' => 3],
            ['id' => uniqid(), 'label' => 'ঘ', 'text' => '', 'answer' => '', 'marks' => 4],
        ];
    }

    public function addCqPart(): void
    {
        $labels = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ', 'ঝ', 'ঞ'];
        $nextLabel = $labels[count($this->cq)] ?? '*';

        $this->cq[] = ['id' => uniqid(), 'label' => $nextLabel, 'text' => '', 'answer' => '', 'marks' => 1];

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

    // ইউজার স্লাগ টাইপ করার সাথে সাথে Livewire অটোমেটিক এটি চেক করবে
    public function updatedSlug($value)
    {
        $this->validateOnly('slug', [
            'slug' => 'required|string|max:255|unique:questions,slug',
        ], [
            'slug.unique' => 'এই স্লাগটি আগে থেকেই ব্যবহৃত হচ্ছে। দয়া করে একটু পরিবর্তন করুন।'
        ]);
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
            $this->marks = 2; // Short & Written Question
            $this->options = [];
        }
        $this->dispatch('refresh-editors');
    }

    public function updatedSubjectId($value)
    {
        $this->sub_subject_id = null;
        $this->topic_id = null;
        $subSubjects = SubSubject::where('subject_id', $value)->get()->map(fn($s) => ['value' => $s->id, 'text' => $s->name])->all();
        $this->dispatch('subSubjectsUpdated', subSubjects: $subSubjects);

        $this->dispatch('topicsUpdated', topics: []);
    }

    public function updatedSubSubjectId($value)
    {
        $this->topic_id = null;
        $topics = $value ? Topic::where('sub_subject_id', $value)->get()->map(fn($c) => ['value' => $c->id, 'text' => $c->name])->all() : [];
        $this->dispatch('topicsUpdated', topics: $topics);
    }

    public function save()
    {
        $this->authorize('create', Question::class);

        $rules = [
            'subject_id' => 'required|exists:subjects,id',
            'sub_subject_id' => 'nullable|exists:sub_subjects,id',
            'topic_id' => 'required_with:sub_subject_id|nullable|exists:topics,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_type' => 'required|in:mcq,cq,short,written', // written যুক্ত করা হয়েছে
            'marks' => 'required|numeric|min:0',
            'tagIds' => 'nullable|array',
            'exam_category_ids' => 'required|array|min:1', // Target Audience Required
            'exam_category_ids.*' => 'exists:exam_categories,id',
            'slug' => ['required', 'string', 'max:255', Rule::unique('questions', 'slug')],
            'image' => 'nullable|image|max:2048', // ইমেজের ভ্যালিডেশন
        ];

        if ($this->question_type === 'mcq') {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.option_text'] = 'required|string';
        }

        $this->validate($rules);

        DB::transaction(function () {
            $extraData = null;

            // টাইপ অনুযায়ী extra_content এ ডাটা সেট
            if ($this->question_type === 'cq') {
                $extraData = $this->cq;
            } elseif ($this->question_type === 'mcq') {
                $extraData = $this->options;
            } elseif ($this->question_type === 'written') {
                $imagePath = null;
                if ($this->image) {
                    $imagePath = $this->image->store('questions', 'public');
                }
                $extraData = ['image' => $imagePath];
            }

            $question = Question::create([
                'subject_id' => $this->subject_id,
                'sub_subject_id' => $this->sub_subject_id ?: null,
                'topic_id' => $this->topic_id ?: null,
                'title' => $this->title,
                'slug' => $this->slug,
                'description' => $this->description,
                'difficulty' => $this->difficulty,
                'question_type' => $this->question_type,
                'marks' => $this->marks,
                'extra_content' => $extraData,
                'user_id' => auth()->id(),
            ]);

            // Tags যুক্ত করা
            if ($this->tagIds) {
                $tagIds = collect($this->tagIds)->map(fn($tag) => is_numeric($tag) ? (int) $tag : Tag::firstOrCreate(['name' => $tag])->id)->toArray();
                $question->tags()->sync($tagIds);
            }

            // Exam Categories (Target Audience) যুক্ত করা
            if (!empty($this->exam_category_ids)) {
                $question->examCategories()->sync($this->exam_category_ids);
            }
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
            'topics' => Topic::where('sub_subject_id', $this->sub_subject_id)->get(),
            'allTags' => Tag::all(),
            'allExamCategories' => ExamCategory::all(), // টার্গেট ক্যাটাগরি পাঠানো হলো
        ])->layout($layout, ['title' => 'Create Question']);
    }
}
