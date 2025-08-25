<?php

namespace App\Livewire\Admin\JobCompanies;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JobCompany;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = [
        'companyDeleted' => '$refresh',
        'deleteCompanyConfirmed' => 'delete',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete($id): void
    {
        JobCompany::findOrFail($id)->delete();
        $this->resetPage();
        $this->dispatch('companyDeleted', message: 'Company deleted successfully.');
    }

    public function render()
    {
        $companies = JobCompany::when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.job-companies.index', [
            'companies' => $companies,
        ])->layout('layouts.admin', ['title' => 'Job Companies']);
    }
}
