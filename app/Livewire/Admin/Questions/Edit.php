<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use App\Models\{Subject, Chapter, Question, Tag};

class Edit extends Component
{
    public Question $question;
    public $subject_id, $chapter_id, $title, $difficulty, $tagIds = [], $options = [];

    public function mount(Question $question)
    {
        $this->question = $question;
        $this->subject_id = $question->subject_id;
        $this->chapter_id = $question->chapter_id;
        $this->title = $question->title;
        $this->difficulty = $question->difficulty;
        $this->tagIds = $question->tags()->pluck('tags.id')->toArray();
        $this->options = $question->options->toArray();
    }

    public function save()
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string',
            'options.*.option_text' => 'required|string',
            'tagIds' => 'nullable|array',
        ]);

        $this->question->update([
            'subject_id' => $this->subject_id,
            'chapter_id' => $this->chapter_id,
            'title' => $this->title,
            'difficulty' => $this->difficulty,
        ]);

        $tagIds = collect($this->tagIds)->map(function ($tag) {
            return is_numeric($tag)
                ? (int) $tag
                : Tag::firstOrCreate(['name' => $tag])->id;
        })->toArray();

        $this->question->tags()->sync($tagIds);

        $this->question->options()->delete();
        foreach ($this->options as $opt) {
            $this->question->options()->create($opt);
        }

        return redirect('/admin/questions')->with('success', 'Question updated.');
    }

    public function updatedSubjectId($value)
    {
        $this->chapter_id = null;
        $chapters = Chapter::where('subject_id', $value)
            ->get()
            ->map(fn($c) => ['value' => $c->id, 'text' => $c->name])
            ->all();
        $this->dispatch('chaptersUpdated', chapters: $chapters);
    }

    public function render()
    {
        return view('livewire.admin.questions.edit', [
            'subjects' => Subject::all(),
            'chapters' => Chapter::where('subject_id', $this->subject_id)->get(),
            'allTags' => Tag::all(),
        ])->layout('layouts.admin', ['title' => 'Edit Question']);
    }
}
