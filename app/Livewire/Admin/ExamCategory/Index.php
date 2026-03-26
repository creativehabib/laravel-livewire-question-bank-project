<?php

namespace App\Livewire\Admin\ExamCategory;

use App\Models\ExamCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str; // <-- এটি ইমপোর্ট করতে হবে

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Modal Properties
    public $name = '';
    public $editId = null;

    protected $listeners = ['deleteExamCategoryConfirmed' => 'delete'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ক্রিয়েট বাটনে ক্লিক করলে ফর্ম রিসেট হবে
    public function openModal()
    {
        $this->reset(['name', 'editId']);
        $this->resetValidation();
    }

    // এডিট বাটনে ক্লিক করলে ডেটা লোড হবে
    public function edit($id)
    {
        $this->resetValidation();
        $examCategory = ExamCategory::findOrFail($id);

        $this->editId = $examCategory->id;
        $this->name = $examCategory->name;

        // মডাল ওপেন করার ইভেন্ট
        $this->dispatch('open-modal');
    }

    // সেভ বা আপডেট করার মেথড
    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('exam_categories', 'name')->ignore($this->editId)
            ],
        ]);

        // Name থেকে অটোমেটিক Slug তৈরি করা হচ্ছে
        $slug = Str::slug($this->name);

        if ($this->editId) {
            // Update
            $examCategory = ExamCategory::find($this->editId);
            $examCategory->update([
                'name' => $this->name,
                'slug' => $slug // <-- Slug আপডেট করা হলো
            ]);
            $message = 'Exam Category updated successfully!';
        } else {
            // Create
            ExamCategory::create([
                'name' => $this->name,
                'slug' => $slug // <-- Slug সেভ করা হলো
            ]);
            $message = 'Exam Category created successfully!';
        }

        $this->reset(['name', 'editId']);
        $this->dispatch('close-modal');
        $this->dispatch('examCategorySaved', message: $message);
    }

    // ডিলিট করার মেথড
    public function delete($id)
    {
        $examCategory = ExamCategory::find($id);
        if ($examCategory) {
            $examCategory->delete();
            $this->resetPage();
            $this->dispatch('examCategoryDeleted', message: 'Exam category deleted successfully.');
        }
    }

    public function render()
    {
        $examCategories = ExamCategory::when($this->search, fn($q) =>
        $q->where('name', 'like', '%'.$this->search.'%')
        )->orderBy('name')->paginate(10);

        return view('livewire.admin.exam-categories.index', [
            'examCategories' => $examCategories,
        ])->layout('layouts.admin', ['title' => 'Manage Exam Categories']);
    }
}
