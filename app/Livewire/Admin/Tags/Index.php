<?php

namespace App\Livewire\Admin\Tags;

use Livewire\Component;
use App\Models\Tag;

class Index extends Component
{
    public $name = '';
    public $editingId = null;
    public $editingName = '';

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:tags,name',
        ]);

        Tag::create([
            'name' => $this->name,
        ]);

        $this->name = '';
    }

    public function delete($id)
    {
        Tag::findOrFail($id)->delete();
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
        return view('livewire.admin.tags.index', [
            'tags' => Tag::all(),
        ])->layout('layouts.app', ['title' => 'Tags']);
    }
}
