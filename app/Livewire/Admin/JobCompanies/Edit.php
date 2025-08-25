<?php

namespace App\Livewire\Admin\JobCompanies;

use Livewire\Component;
use App\Models\JobCompany;
use Illuminate\Support\Str;

class Edit extends Component
{
    public JobCompany $company;
    public $name;
    public $slug;
    public $logo;
    public $details;

    public function mount(JobCompany $company)
    {
        $this->company = $company;
        $this->name = $company->name;
        $this->slug = $company->slug;
        $this->logo = $company->logo;
        $this->details = $company->details;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_companies,slug,'.$this->company->id,
            'logo' => 'nullable|string|max:255',
            'details' => 'nullable|string',
        ]);

        $this->company->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $this->logo,
            'details' => $this->details,
        ]);

        return redirect()->route('admin.job-companies.index')
            ->with('success', 'Company updated.');
    }

    public function render()
    {
        return view('livewire.admin.job-companies.edit')
            ->layout('layouts.admin', ['title' => 'Edit Job Company']);
    }
}
