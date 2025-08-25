<?php

namespace App\Livewire\Admin\JobCategories;

use Livewire\Component;
use App\Models\JobCategory;
use Illuminate\Support\Str;

class Edit extends Component
{
    public JobCategory $category;
    public $name;
    public $slug;
    public $description;
    public $image;

    public function mount(JobCategory $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->image = $category->image;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_categories,slug,' . $this->category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
        ]);

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
        ]);

        return redirect()->route('admin.job-categories.index')
            ->with('success', 'Category updated.');
    }

    public function render()
    {
        return view('livewire.admin.job-categories.edit')
            ->layout('layouts.admin', ['title' => 'Edit Job Category']);
    }
}
