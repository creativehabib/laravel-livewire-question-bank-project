<?php

namespace App\Livewire\Admin\Jobs;

use Livewire\Component;
use App\Models\JobPost;
use App\Enums\JobStatus;
use Illuminate\Support\Str;

class Create extends Component
{
    public $title;
    public $slug;
    public $category_id;
    public $company_name;
    public $summary;
    public $description;
    public $deadline;
    public $posted_at;
    public $status = JobStatus::DRAFT->value;
    public $featured = false;
    public $cover_image;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_posts,slug',
            'category_id' => 'nullable|integer',
            'company_name' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'posted_at' => 'nullable|date',
            'status' => 'required|in:'.implode(',', array_column(JobStatus::cases(), 'value')),
            'featured' => 'boolean',
            'cover_image' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        JobPost::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'company_name' => $this->company_name,
            'summary' => $this->summary,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'posted_at' => $this->posted_at,
            'status' => $this->status,
            'featured' => $this->featured,
            'cover_image' => $this->cover_image,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
        ]);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job created.');
    }

    public function render()
    {
        return view('livewire.admin.jobs.create')
            ->layout('layouts.admin', ['title' => 'Create Job']);
    }
}
