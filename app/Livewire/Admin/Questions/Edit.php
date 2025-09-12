<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\{Subject, Chapter, Question, Tag};

class Edit extends Component
{
    use AuthorizesRequests;

    public Question $question;
    public $subject_id, $chapter_id, $title, $difficulty, $tagIds = [], $options = [];

    public function mount(Question $question)
    {
        $this->authorize('update', $question);

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
        $this->authorize('update', $this->question);

        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'title' => 'required|string',
            'options.*.option_text' => 'required|string',
            'tagIds' => 'nullable|array',
        ]);

        $this->question->update([
            'subject_id' => $this->subject_id,
            'chapter_id' => $this->chapter_id ?: null,
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

        $route = auth()->user()->isTeacher() ? 'teacher.questions.index' : 'admin.questions.index';
        return redirect()->route($route)->with('success', 'Question updated.');
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
        $layout = auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.panel';

        return view('livewire.admin.questions.edit', [
            'subjects' => Subject::all(),
            'chapters' => Chapter::where('subject_id', $this->subject_id)->get(),
            'allTags' => Tag::all(),
        ])->layout($layout, ['title' => 'Edit Question']);
    }
}
