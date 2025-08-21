<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Services\QuestionViewService;

class Practice extends Component
{
    public $current, $selectedOption;

    /**
     * Service used to record unique question views.
     */
    protected QuestionViewService $views;

    /**
     * Bootstrap the component with the view service on every request.
     */
    public function boot(QuestionViewService $views): void
    {
        $this->views = $views;
    }

    public function mount(): void
    {
        $this->loadRandom();
    }

    public function loadRandom()
    {
        $this->current = Question::with('options')->inRandomOrder()->first();
        if ($this->current) {
            $this->views->record($this->current, request()->ip());
        }
        $this->selectedOption = null;
    }

    public function selectOption($id)
    {
        $this->selectedOption = $id;
    }

    public function render()
    {
        return view('livewire.practice')->layout('layouts.admin', ['title' => 'Practice']);
    }
}

