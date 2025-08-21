<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use App\Models\{Subject, Chapter, Question, Option, Tag};

class Create extends Component
{
    public $subject_id, $chapter_id, $title, $difficulty = 'easy', $tagIds = [];
    public $options = [
        ['option_text' => '', 'is_correct' => false],
        ['option_text' => '', 'is_correct' => false],
        ['option_text' => '', 'is_correct' => false],
        ['option_text' => '', 'is_correct' => false],
    ];

    public function updatedSubjectId()
    {
        $this->chapter_id = null;
    }

    public function save()
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string',
            'options.*.option_text' => 'required|string',
            'options' => 'array|min:2',
            'tagIds' => 'nullable|array',
        ]);

        $question = Question::create([
            'subject_id' => $this->subject_id,
            'chapter_id' => $this->chapter_id,
            'title' => $this->title,
            'difficulty' => $this->difficulty,
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

        return redirect('/admin/questions')->with('success', 'Question created.');
    }

    public function render()
    {
        return view('livewire.admin.questions.create', [
            'subjects' => Subject::all(),
            'chapters' => Chapter::where('subject_id', $this->subject_id)->get(),
            'allTags' => Tag::all(),
        ])->layout('layouts.admin', ['title' => 'Create Question']);
    }
}
