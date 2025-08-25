<?php

namespace App\Livewire\Admin\Jobs;

use Livewire\Component;
use App\Models\JobPost;
use App\Models\JobCategory;
use App\Enums\JobStatus;
use Illuminate\Support\Str;

class Edit extends Component
{
    public JobPost $job;
    public $title;
    public $slug;
    public $category_id;
    public $company_name;
    public $summary;
    public $description;
    public $deadline;
    public $posted_at;
    public $status;
    public $featured;
    public $cover_image;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;

    public function mount(JobPost $job)
    {
        $this->job = $job;
        $this->title = $job->title;
        $this->slug = $job->slug;
        $this->category_id = $job->category_id;
        $this->company_name = $job->company_name;
        $this->summary = $job->summary;
        $this->description = $job->description;
        $this->deadline = optional($job->deadline)->format('Y-m-d');
        $this->posted_at = optional($job->posted_at)->format('Y-m-d\TH:i');
        $this->status = $job->status->value;
        $this->featured = $job->featured;
        $this->cover_image = $job->cover_image;
        $this->seo_title = $job->seo_title;
        $this->seo_description = $job->seo_description;
        $this->seo_keywords = $job->seo_keywords;
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_posts,slug,' . $this->job->id,
            'category_id' => 'nullable|exists:job_categories,id',
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

        $this->job->update([
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
            ->with('success', 'Job updated.');
    }

    public function render()
    {
        return view('livewire.admin.jobs.edit', [
            'categories' => JobCategory::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Edit Job']);
    }
}
