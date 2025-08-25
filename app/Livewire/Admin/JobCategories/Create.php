<?php

namespace App\Livewire\Admin\JobCategories;

use Livewire\Component;
use App\Models\JobCategory;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name;
    public $slug;
    public $description;
    public $image;

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
        ]);

        JobCategory::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
        ]);

        return redirect()->route('admin.job-categories.index')
            ->with('success', 'Category created.');
    }

    public function render()
    {
        return view('livewire.admin.job-categories.create')
            ->layout('layouts.admin', ['title' => 'Create Job Category']);
    }
}
