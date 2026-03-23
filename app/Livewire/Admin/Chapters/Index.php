<?php

namespace App\Livewire\Admin\Chapters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\SubSubject;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $subjectId = '';

    // Modal Properties
    public $name = '';
    public $modalSubjectId = '';
    public $modalSubSubjectId = null; // চ্যাপ্টারের ক্ষেত্রে সাব-সাবজেক্ট থাকতে পারে
    public $editId = null;

    protected $listeners = ['deleteChapterConfirmed' => 'delete'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSubjectId(): void
    {
        $this->resetPage();
    }

    // মডালে যখন সাবজেক্ট পরিবর্তন করা হবে, তখন সাব-সাবজেক্ট রিসেট হয়ে যাবে
    public function updatedModalSubjectId()
    {
        $this->modalSubSubjectId = null;
    }

    // ক্রিয়েট বাটনে ক্লিক করলে ফর্ম রিসেট হবে
    public function openModal()
    {
        $this->reset(['name', 'modalSubjectId', 'modalSubSubjectId', 'editId']);
        $this->resetValidation();
    }

    // এডিট বাটনে ক্লিক করলে ডেটা লোড হবে
    public function edit($id)
    {
        $this->resetValidation();
        $chapter = Chapter::findOrFail($id);

        $this->editId = $chapter->id;
        $this->modalSubjectId = $chapter->subject_id;
        $this->modalSubSubjectId = $chapter->sub_subject_id;
        $this->name = $chapter->name;

        // মডাল ওপেন করার ইভেন্ট
        $this->dispatch('open-modal');
    }

    // সেভ বা আপডেট করার মেথড
    public function save()
    {
        $this->validate([
            'modalSubjectId' => 'required|exists:subjects,id',
            'modalSubSubjectId' => 'nullable|exists:sub_subjects,id',
            'name' => [
                'required',
                'string',
                Rule::unique('chapters', 'name')
                    ->where('subject_id', $this->modalSubjectId)
                    ->where('sub_subject_id', $this->modalSubSubjectId)
                    ->ignore($this->editId) // আপডেটের সময় নিজের আইডি ইগনোর করবে
            ],
        ]);

        if ($this->editId) {
            // Update
            $chapter = Chapter::find($this->editId);
            $chapter->update([
                'subject_id' => $this->modalSubjectId,
                'sub_subject_id' => $this->modalSubSubjectId ?: null, // খালি থাকলে null সেভ হবে
                'name' => $this->name,
            ]);
            $message = 'Chapter updated successfully!';
        } else {
            // Create
            Chapter::create([
                'subject_id' => $this->modalSubjectId,
                'sub_subject_id' => $this->modalSubSubjectId ?: null,
                'name' => $this->name,
            ]);
            $message = 'Chapter created successfully!';
        }

        $this->reset(['name', 'modalSubjectId', 'modalSubSubjectId', 'editId']);
        $this->dispatch('close-modal');
        $this->dispatch('chapterSaved', message: $message);
    }

    // ডিলিট করার মেথড
    public function delete($id)
    {
        $chapter = Chapter::find($id);
        if ($chapter) {
            $chapter->delete();
            $this->resetPage();
            $this->dispatch('chapterDeleted', message: 'Chapter deleted successfully.');
        }
    }

    public function render()
    {
        $chapters = Chapter::with('subject', 'subSubject')
            ->when($this->subjectId, fn($q) => $q->where('subject_id', $this->subjectId))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(10);

        // মডালের জন্য ডাইনামিক সাব-সাবজেক্ট লোড করা (যদি সাবজেক্ট সিলেক্ট করা থাকে)
        $modalSubSubjects = $this->modalSubjectId
            ? SubSubject::where('subject_id', $this->modalSubjectId)->orderBy('name')->get()
            : [];

        return view('livewire.admin.chapters.index', [
            'chapters' => $chapters,
            'subjects' => Subject::orderBy('name')->get(),
            'modalSubSubjects' => $modalSubSubjects, // মডালে পাঠানোর জন্য
        ])->layout('layouts.admin', ['title' => 'Manage Chapters']);
    }
}
