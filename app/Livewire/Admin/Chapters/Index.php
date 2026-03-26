<?php

namespace App\Livewire\Admin\Chapters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $subjectId = '';

    // --- Clone Modal Properties ---
    public $showCloneModal = false;
    public $cloneSourceSubjectId = '';
    public $cloneTargetSubjectId = '';

    // Modal Properties
    public $name = '';
    public $modalSubjectId = '';
    public $editId = null; // এডিট করার জন্য আইডি ট্র্যাক করবে

    protected $listeners = ['deleteChapterConfirmed' => 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSubjectId()
    {
        $this->resetPage();
    }

    // ক্লোন মডাল ওপেন করা
    public function openCloneModal()
    {
        $this->reset(['cloneSourceSubjectId', 'cloneTargetSubjectId']);
        $this->resetValidation();
        $this->showCloneModal = true;
    }

    // ক্লোন করার মূল লজিক
    public function cloneChapters()
    {
        $this->validate([
            'cloneSourceSubjectId' => 'required|exists:subjects,id',
            'cloneTargetSubjectId' => 'required|exists:subjects,id|different:cloneSourceSubjectId'
        ], [
            'cloneTargetSubjectId.different' => 'Source এবং Target সাবজেক্ট একই হতে পারবে না!'
        ]);

        // সোর্স সাবজেক্টের সব সাব-সাবজেক্ট খুঁজে বের করা
        $chaptersToCopy = Chapter::where('subject_id', $this->cloneSourceSubjectId)->get();

        if ($chaptersToCopy->isEmpty()) {
            $this->addError('cloneSourceSubjectId', 'এই সাবজেক্টে কপি করার মতো কোনো সাব-সাবজেক্ট নেই!');
            return;
        }

        $count = 0;
        foreach ($chaptersToCopy as $sub) {
            // আগে চেক করা হবে টার্গেট সাবজেক্টে একই নামের কিছু আছে কি না
            $exists = Chapter::where('subject_id', $this->cloneTargetSubjectId)
                ->where('name', $sub->name)
                ->exists();

            if (!$exists) {
                Chapter::create([
                    'subject_id' => $this->cloneTargetSubjectId,
                    'name' => $sub->name,
                ]);
                $count++;
            }
        }

        $this->showCloneModal = false;
        $this->resetPage(); // টেবিল রিফ্রেশ করার জন্য
        $this->dispatch('chapterSaved', message: "{$count} টি সাব-সাবজেক্ট সফলভাবে কপি হয়েছে!");
    }


    // ক্রিয়েট বাটনে ক্লিক করলে ফর্ম সম্পূর্ণ রিসেট হবে
    public function openModal()
    {
        $this->reset(['name', 'modalSubjectId', 'editId']);
        $this->resetValidation(); // সব এরর মেসেজ ক্লিয়ার করে দিবে
    }

    // এডিট বাটনে ক্লিক করলে ডেটা মডালে লোড হবে
    public function edit($id)
    {
        $this->resetValidation();
        $chapter = Chapter::findOrFail($id);

        $this->editId = $chapter->id;
        $this->modalSubjectId = $chapter->subject_id;
        $this->name = $chapter->name;

        // মডাল ওপেন করার ইভেন্ট পাঠানো হচ্ছে
        $this->dispatch('open-modal');
    }

    // নতুন সেভ বা পুরানো ডেটা আপডেট করার মেথড
    public function save()
    {
        $this->validate([
            'modalSubjectId' => 'required|exists:subjects,id',
            'name' => [
                'required',
                'string',
                Rule::unique('chapters', 'name')
                    ->where('subject_id', $this->modalSubjectId)
                    ->ignore($this->editId) // আপডেটের সময় নিজের আইডি ইগনোর করবে যাতে এরর না দেয়
            ],
        ]);

        if ($this->editId) {
            // যদি editId থাকে, তবে ডেটা আপডেট হবে
            $chapter = Chapter::find($this->editId);
            $chapter->update([
                'subject_id' => $this->modalSubjectId,
                'name' => $this->name,
            ]);
            $message = 'Sub subject updated successfully!';
        } else {
            // editId না থাকলে নতুন তৈরি হবে
            Chapter::create([
                'subject_id' => $this->modalSubjectId,
                'name' => $this->name,
            ]);
            $message = 'Sub subject created successfully!';
        }

        // ফর্ম রিসেট করা
        $this->reset(['name', 'modalSubjectId', 'editId']);

        // মডাল বন্ধ করার জন্য ব্রাউজারে ইভেন্ট পাঠানো
        $this->dispatch('close-modal');
        $this->dispatch('chapterSaved', message: $message);
    }

    // ডিলিট করার মেথড
    public function delete($id)
    {
        $chapter = \App\Models\Chapter::find($id);
        if ($chapter) {
            $chapter->delete();
            $this->dispatch('chapterDeleted', message: 'Sub subject deleted successfully.');
        }
    }

    public function render()
    {
        $query = Chapter::query()->with('subject');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->subjectId) {
            $query->where('subject_id', $this->subjectId);
        }

        return view('livewire.admin.chapters.index', [
            'chapters' => $query->latest()->paginate(10),
            'subjects' => Subject::orderBy('name')->get(), // ফিল্টার এবং মডাল উভয়ের জন্য
        ])->layout('layouts.admin', ['title' => 'Chapters']);
    }
}
