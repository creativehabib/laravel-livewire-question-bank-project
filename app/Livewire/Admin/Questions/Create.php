<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\{Subject, SubSubject, Chapter, Question, Option, Tag};

class Create extends Component
{
    use AuthorizesRequests;

    public $subject_id, $sub_subject_id, $chapter_id, $title, $difficulty = 'easy', $tagIds = [];
    public $options = [
        ['option_text' => '', 'is_correct' => false],
        ['option_text' => '', 'is_correct' => false],
        ['option_text' => '', 'is_correct' => false],
        ['option_text' => '', 'is_correct' => false],
    ];

    public function updatedSubjectId($value)
    {
        $this->sub_subject_id = null;
        $this->chapter_id = null;

        $subSubjects = SubSubject::where('subject_id', $value)
            ->get()
            ->map(fn($s) => ['value' => $s->id, 'text' => $s->name])
            ->all();
        $this->dispatch('subSubjectsUpdated', subSubjects: $subSubjects);

        $this->dispatch('chaptersUpdated', chapters: []);
    }

    public function updatedSubSubjectId($value)
    {
        $this->chapter_id = null;
        $chapters = Chapter::where('sub_subject_id', $value)
            ->get()
            ->map(fn($c) => ['value' => $c->id, 'text' => $c->name])
            ->all();
        $this->dispatch('chaptersUpdated', chapters: $chapters);
    }

    public function save()
    {
        $this->authorize('create', Question::class);

        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'sub_subject_id' => 'nullable|exists:sub_subjects,id',
            'chapter_id' => 'required_with:sub_subject_id|nullable|exists:chapters,id',
            'title' => 'required|string',
            'options.*.option_text' => 'required|string',
            'options' => 'array|min:2',
            'tagIds' => 'nullable|array',
        ]);

        $question = Question::create([
            'subject_id' => $this->subject_id,
            'sub_subject_id' => $this->sub_subject_id ?: null,
            'chapter_id' => $this->chapter_id ?: null,
            'title' => $this->title,
            'difficulty' => $this->difficulty,
            'user_id' => auth()->id(),
        ]);

        if ($this->tagIds) {
            $tagIds = collect($this->tagIds)->map(function ($tag) {
                return is_numeric($tag)
                    ? (int) $tag
                    : Tag::firstOrCreate(['name' => $tag])->id;
            })->toArray();

            $question->tags()->sync($tagIds);
        }

        foreach ($this->options as $opt) {
            $question->options()->create($opt);
        }

        $route = auth()->user()->isTeacher() ? 'teacher.questions.index' : 'admin.questions.index';
        return redirect()->route($route)->with('success', 'Question created.');
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
