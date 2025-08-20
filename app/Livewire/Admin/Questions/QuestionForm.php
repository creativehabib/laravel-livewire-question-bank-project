<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use App\Models\{Subject, Chapter, Question, Tag};

class QuestionForm extends Component
{
    public $questionId;  // যদি edit হয় তাহলে এই আইডি আসবে
    public $subject_id, $chapter_id, $title, $difficulty = 'easy', $tagIds = [];
    public $options = [];

    public function mount($id = null)
    {
        $this->subject_id = '';
        $this->chapter_id = '';

        if ($id) {
            $this->questionId = $id;
            $q = Question::with('options', 'tags')->findOrFail($id);

            $this->subject_id = $q->subject_id;
            $this->chapter_id = $q->chapter_id;
            $this->title = $q->title;
            $this->difficulty = $q->difficulty;
            $this->tagIds = $q->tags()->pluck('tags.id')->toArray();
            $this->options = $q->options->toArray();
        } else {
            $this->options = [
                ['option_text' => '', 'is_correct' => false],
                ['option_text' => '', 'is_correct' => false],
                ['option_text' => '', 'is_correct' => false],
                ['option_text' => '', 'is_correct' => false],
            ];
        }
    }

    public function save()
    {
        $data = $this->validate([
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|string',
            'difficulty' => 'required',
            'tagIds' => 'nullable|array',
            'options.*.option_text' => 'required|string',
        ]);
        $tagIds = collect($this->tagIds)->map(function ($tag) {
            if (is_numeric($tag)) {
                return (int) $tag;
            }
            return Tag::firstOrCreate(['name' => $tag])->id;
        })->toArray();

        if ($this->questionId) {
            $q = Question::findOrFail($this->questionId);
            $q->update([
                'subject_id' => $this->subject_id,
                'chapter_id' => $this->chapter_id,
                'title' => $this->title,
                'difficulty' => $this->difficulty,
            ]);
            $q->tags()->sync($tagIds);
            $q->options()->delete();
            $q->options()->createMany($this->options);
        } else {
            $q = Question::create([
                'subject_id' => $this->subject_id,
                'chapter_id' => $this->chapter_id,
                'title' => $this->title,
                'difficulty' => $this->difficulty,
            ]);
            $q->tags()->sync($tagIds);
            $q->options()->createMany($this->options);
        }

        session()->flash('success', 'Question saved successfully.');
        return redirect()->route('admin.questions.index');
    }

    public function render()
    {
        return view('livewire.admin.questions.question-form', [
            'subjects' => Subject::all(),
            'chapters' => Chapter::all(),
            'allTags' => Tag::all(),
        ]);
    }
}
