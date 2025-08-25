<?php

namespace App\Livewire\Admin\JobCategories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JobCategory;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = [
        'categoryDeleted' => '$refresh',
        'deleteCategoryConfirmed' => 'delete',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete($id): void
    {
        JobCategory::findOrFail($id)->delete();
        $this->resetPage();
        $this->dispatch('categoryDeleted', message: 'Category deleted successfully.');
    }

    public function render()
    {
        $categories = JobCategory::when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.job-categories.index', [
            'categories' => $categories,
        ])->layout('layouts.admin', ['title' => 'Job Categories']);
    }
}
