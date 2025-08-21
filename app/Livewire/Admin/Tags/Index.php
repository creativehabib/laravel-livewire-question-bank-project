<?php

namespace App\Livewire\Admin\Tags;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tag;

class Index extends Component
{
    use WithPagination;

    public $name = '';
    public $editingId = null;
    public $editingName = '';
    public $search = '';

    protected $listeners = ['tagDeleted' => '$refresh'];

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:tags,name',
        ]);

        Tag::create([
            'name' => $this->name,
        ]);

        $this->name = '';
        $this->resetPage();
    }

    public function delete($id)
    {
        Tag::findOrFail($id)->delete();

        $this->dispatch('tagDeleted');
        session()->flash('success', 'Tag deleted successfully.');
        $this->resetPage();
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $this->editingId = $tag->id;
        $this->editingName = $tag->name;
    }

    public function update()
    {
        $this->validate([
            'editingName' => 'required|string|unique:tags,name,' . $this->editingId,
        ]);

        Tag::findOrFail($this->editingId)->update(['name' => $this->editingName]);

        $this->editingId = null;
        $this->editingName = '';
    }

    public function cancelEdit()
    {
        $this->editingId = null;
        $this->editingName = '';
    }

    public function render()
    {
        $tags = Tag::when($this->search, fn($q) =>
                $q->where('name', 'like', '%'.$this->search.'%')
            )
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.tags.index', [
            'tags' => $tags,
        ])->layout('layouts.admin', ['title' => 'Tags']);
    }
}
