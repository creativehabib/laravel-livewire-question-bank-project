<?php

namespace App\Livewire\Admin\Media;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    public $editingId = null;
    public $editingName = '';

    public $replacingId = null;
    public $replaceFile;

    protected $listeners = [
        'mediaDeleted' => '$refresh',
        'refreshMedia' => '$refresh',
        'deleteMediaConfirmed' => 'delete',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }



    public function edit($id): void
    {
        $media = Media::findOrFail($id);
        $this->editingId = $media->id;
        $this->editingName = $media->name;
    }

    public function update(): void
    {
        $this->validate([
            'editingName' => 'required|string|max:255',
        ]);

        Media::findOrFail($this->editingId)->update(['name' => $this->editingName]);

        $this->editingId = null;
        $this->editingName = '';
        $this->dispatch('mediaUpdated', message: 'Media updated successfully.');
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->editingName = '';
    }

    public function startReplace($id): void
    {
        $this->replacingId = $id;
    }

    public function replace(): void
    {
        $this->validate([
            'replaceFile' => 'required|file|max:10240',
        ]);

        $media = Media::findOrFail($this->replacingId);
        Storage::disk('public')->delete($media->path);

        $path = $this->replaceFile->store('media', 'public');
        $mime = $this->replaceFile->getMimeType();
        $size = $this->replaceFile->getSize();
        $dimensions = str_starts_with($mime, 'image/') ? getimagesize($this->replaceFile->getRealPath()) : null;

        $media->update([
            'filename' => basename($path),
            'mime_type' => $mime,
            'path' => $path,
            'size' => $size,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
        ]);

        $this->replacingId = null;
        $this->replaceFile = null;
        $this->dispatch('mediaReplaced', message: 'Media replaced successfully.');
    }

    public function cancelReplace(): void
    {
        $this->replacingId = null;
        $this->replaceFile = null;
    }

    public function delete($id): void
    {
        $media = Media::findOrFail($id);
        Storage::disk('public')->delete($media->path);
        $media->delete();
        $this->resetPage();
        $this->dispatch('mediaDeleted', message: 'Media deleted successfully.');
    }

    public function render()
    {
        $mediaItems = Media::when($this->search, fn($q) =>
                $q->where('name', 'like', '%'.$this->search.'%')
            )
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.media.index', [
            'mediaItems' => $mediaItems,
        ])->layout('layouts.admin', ['title' => 'Media Manager']);
    }
}

