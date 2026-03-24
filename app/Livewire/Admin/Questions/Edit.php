<?php

namespace App\Livewire\Admin\Questions;

use App\Livewire\Traits\SlugValidationTrait;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\{Subject, SubSubject, Chapter, Question, Tag};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // <-- এটি যোগ করা হয়েছে

class Edit extends Component
{
    use AuthorizesRequests, SlugValidationTrait;

    public Question $question;
    public $subject_id, $sub_subject_id, $chapter_id, $title, $description, $difficulty, $question_type = 'mcq', $marks = 1, $tagIds = [], $options = [];
    public $cq = [];
    public $slug;

    public function mount(Question $question)
    {
        $this->authorize('update', $question);
        $this->question = $question;

        $this->subject_id = $question->subject_id;
        $this->sub_subject_id = $question->sub_subject_id;
        $this->chapter_id = $question->chapter_id;
        $this->title = $question->title;
        $this->slug = $question->slug; // <-- Database থেকে Slug লোড করা হলো
        $this->description = $question->description;
        $this->difficulty = $question->difficulty;
        $this->question_type = $question->question_type ?? 'mcq';
        $this->marks = $question->marks ?? 1;
        $this->tagIds = $question->tags()->pluck('tags.id')->toArray();

        $extraData = is_string($question->extra_content) ? json_decode($question->extra_content, true) : $question->extra_content;

        if ($this->question_type === 'cq') {
            $this->cq = is_array($extraData) && !empty($extraData) ? $extraData : [];
            if (empty($this->cq)) $this->setCqDefaults();
            $this->resetToMcq();
        } elseif ($this->question_type === 'mcq') {
            if (is_array($extraData) && !empty($extraData)) {
                $this->options = $extraData;
            } else {
                $this->options = $question->options->toArray();
            }

            if (empty($this->options)) $this->resetToMcq();
            $this->setCqDefaults();
        } else {
            $this->resetToMcq();
            $this->setCqDefaults();
        }
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

        // এখানে 'answer' => '' যুক্ত করা হয়েছে
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
            'slug' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('questions', 'slug')->ignore($this->question->id ?? null)
            ],
        ], [
            'slug.unique' => 'এই স্লাগটি আগে থেকেই ব্যবহৃত হচ্ছে। দয়া করে একটু পরিবর্তন করুন।'
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
            $this->marks = 2;
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
        $this->authorize('update', $this->question);

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
            'slug' => ['required', 'string', 'max:255', Rule::unique('questions', 'slug')->ignore($this->question->id)], // <-- Unique (নিজের আইডি ছাড়া)
        ];

        if ($this->question_type === 'mcq') {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.option_text'] = 'required|string';
        }

        $this->validate($rules);

        DB::transaction(function () {
            $extraData = null;
            if ($this->question_type === 'cq') {
                $extraData = $this->cq;
            } elseif ($this->question_type === 'mcq') {
                $extraData = $this->options;
            }

            $this->question->update([
                'subject_id' => $this->subject_id,
                'sub_subject_id' => $this->sub_subject_id ?: null,
                'chapter_id' => $this->chapter_id ?: null,
                'title' => $this->title,
                'slug' => $this->slug, // <-- Update Slug
                'description' => $this->description,
                'difficulty' => $this->difficulty,
                'question_type' => $this->question_type,
                'marks' => $this->marks,
                'extra_content' => $extraData,
            ]);

            $tagIds = collect($this->tagIds)->map(fn($tag) => is_numeric($tag) ? (int) $tag : Tag::firstOrCreate(['name' => $tag])->id)->toArray();
            $this->question->tags()->sync($tagIds);

            $this->question->options()->delete();
        });

        $route = auth()->user()->isTeacher() ? 'teacher.questions.index' : 'admin.questions.index';
        return redirect()->route($route)->with('success', 'Question updated successfully.');
    }

    public function render()
    {
        $layout = auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.panel';
        return view('livewire.admin.questions.edit', [
            'subjects' => Subject::all(),
            'subSubjects' => SubSubject::where('subject_id', $this->subject_id)->get(),
            'chapters' => Chapter::where('sub_subject_id', $this->sub_subject_id)->get(),
            'allTags' => Tag::all(),
        ])->layout($layout, ['title' => 'Edit Question']);
    }
}
