<?php

namespace App\Livewire\Admin;

use App\Models\Question;
use Livewire\Component;

class Questions extends Component
{
    public $title, $content, $options = [], $answer;

    public function addOption()
    {
        $this->options[] = '';
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'content' => 'required',
            'options' => 'required|array|min:2',
            'answer' => 'required'
        ]);

        Question::create([
            'title'   => $this->title,
            'content' => $this->content,
            'options' => json_encode($this->options), // Array → JSON
            'answer'  => $this->answer,
        ]);

        session()->flash('success', 'Question created successfully.');
        $this->reset(['title', 'content', 'options', 'answer']);
    }
    public function render()
    {
        return view('livewire.admin.questions',[
            'questions' => Question::latest()->get()
        ]);
    }
}
