<?php

namespace App\Livewire\Admin\JobCompanies;

use Livewire\Component;
use App\Models\JobCompany;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name;
    public $slug;
    public $logo;
    public $details;

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_companies,slug',
            'logo' => 'nullable|string|max:255',
            'details' => 'nullable|string',
        ]);

        JobCompany::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $this->logo,
            'details' => $this->details,
        ]);

        return redirect()->route('admin.job-companies.index')
            ->with('success', 'Company created.');
    }

    public function render()
    {
        return view('livewire.admin.job-companies.create')
            ->layout('layouts.admin', ['title' => 'Create Job Company']);
    }
}
