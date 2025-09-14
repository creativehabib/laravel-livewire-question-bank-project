<?php

namespace App\Livewire\Admin\Jobs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JobPost;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = [
        'jobDeleted' => '$refresh',
        'deleteJobConfirmed' => 'delete',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete($id): void
    {
        JobPost::findOrFail($id)->delete();
        $this->resetPage();
        $this->dispatch('jobDeleted', message: 'Job deleted successfully.');
    }

    public function render()
    {
        $jobs = JobPost::when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.jobs.index', [
            'jobs' => $jobs,
        ])->layout('layouts.admin', ['title' => 'Manage Jobs']);
    }
}
