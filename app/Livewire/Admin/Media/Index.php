<?php

namespace App\Livewire\Admin\Media;

use App\Models\Media;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Uploader properties
    public $file;
    public $url = '';

    // Details Drawer properties
    public ?Media $selectedMedia = null;
    public $newName = '';
    public $newFile;

    // Open Details Drawer with selected media
    public function selectMedia($mediaId)
    {
        $this->selectedMedia = Media::findOrFail($mediaId);
        $this->newName = $this->selectedMedia->name;
        $this->dispatch('open-details-drawer');
    }

    // Update the name of the selected media
    public function updateMediaName()
    {
        if ($this->selectedMedia) {
            $this->validate(['newName' => 'required|string|max:255']);
            $this->selectedMedia->name = $this->newName;
            $this->selectedMedia->save();
            $this->dispatch('mediaUpdated', message: 'Name updated successfully.');
        }
    }

    // Replace the image of the selected media
    public function updatedNewFile()
    {
        if (!$this->selectedMedia) return;
        $this->validate(['newFile' => 'required|image|max:10240']); // 10MB Max

        Storage::disk('public')->delete($this->selectedMedia->path);

        $path = $this->newFile->store('media', 'public');

        $this->selectedMedia->update([
            'name'      => pathinfo($this->newFile->getClientOriginalName(), PATHINFO_FILENAME),
            'filename'  => basename($path),
            'mime_type' => $this->newFile->getMimeType(),
            'path'      => $path,
            'size'      => $this->newFile->getSize(),
            'width'     => getimagesize($this->newFile->getRealPath())[0] ?? null,
            'height'    => getimagesize($this->newFile->getRealPath())[1] ?? null,
        ]);

        $this->reset('newFile');
        $this->selectMedia($this->selectedMedia->id);
        $this->dispatch('mediaUpdated', message: 'Image replaced successfully.');
    }

    // Handle file upload from device
    public function updatedFile()
    {
        $this->validate(['file' => 'required|image|max:10240']);
        try {
            $path = $this->file->store('media', 'public');
            $this->saveMedia($path, $this->file->getClientOriginalName(), $this->file->getMimeType(), $this->file->getSize(), getimagesize($this->file->getRealPath()));
            $this->dispatch('mediaUpdated', message: 'File uploaded successfully!');
            $this->reset('file');
        } catch (Throwable $e) {
            logger()->error('Livewire File Upload Failed: ' . $e->getMessage());
            $this->dispatch('mediaUpdated', message: 'File upload failed!', type: 'error');
        }
    }

    /**
     * Handle file upload from URL.
     * This version includes robust error handling and server checks.
     */
    public function uploadFromUrl()
    {
        $this->validate(['url' => 'required|url']);

        if (!extension_loaded('gd')) {
            $this->dispatch('mediaUpdated', message: 'GD Library is not enabled on the server.', type: 'error');
            return;
        }

        $tempFilePath = null;

        try {
            // SSL ভেরিফিকেশন বন্ধ করে ডাউনলোড করার চেষ্টা
            $response = Http::withoutVerifying()->timeout(30)->get($this->url);

            if (! $response->successful()) {
                $this->dispatch('mediaUpdated', message: 'Provided URL is not accessible.', type: 'error');
                return;
            }

            $content = $response->body();
            $mime = $response->header('Content-Type') ?? 'application/octet-stream';

            if (!Str::startsWith($mime, 'image/')) {
                $this->dispatch('mediaUpdated', message: 'The provided URL does not point to a valid image.', type: 'error');
                return;
            }

            $tempFilename = 'temp/' . Str::random(40);
            Storage::disk('local')->put($tempFilename, $content);
            $tempFilePath = Storage::disk('local')->path($tempFilename);

            $dimensions = @getimagesize($tempFilePath);
            if ($dimensions === false) {
                $dimensions = [null, null];
            }

            $extension = Str::afterLast(parse_url($this->url, PHP_URL_PATH), '.');
            if (!$extension || strlen($extension) > 5) {
                $parts = explode('/', $mime);
                $extension = end($parts) ?? 'tmp';
            }

            $filename = Str::random(40) . '.' . $extension;
            $path = 'media/' . $filename;
            Storage::disk('public')->put($path, $content);

            $this->saveMedia($path, parse_url($this->url, PHP_URL_PATH), $mime, strlen($content), $dimensions);

            $this->dispatch('mediaUpdated', message: 'File uploaded from URL successfully!');
            $this->reset('url');

        } catch (Throwable $e) {
            logger()->error('URL Upload Failed: ' . $e->getMessage());
            $this->dispatch('mediaUpdated', message: 'Upload from URL failed! Check logs.', type: 'error');
        } finally {
            if ($tempFilePath && Storage::disk('local')->exists($tempFilename)) {
                Storage::disk('local')->delete($tempFilename);
            }
        }
    }

    private function saveMedia($path, $originalName, $mime, $size, $dimensions)
    {
        Media::create([
            'name' => pathinfo($originalName, PATHINFO_FILENAME),
            'filename' => basename($path),
            'mime_type' => $mime,
            'path' => $path,
            'disk' => 'public',
            'size' => $size,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'created_by' => auth()->id(),
        ]);
    }

    #[On('refreshMedia')]
    public function render()
    {
        $mediaItems = Media::latest()->paginate(18);
        return view('livewire.admin.media.index', compact('mediaItems'));
    }

    #[On('deleteMediaConfirmed')]
    public function deleteMediaConfirmed($id)
    {
        $media = Media::findOrFail($id);
        Storage::disk('public')->delete($media->path);
        $media->delete();

        if ($this->selectedMedia && $this->selectedMedia->id === $id) {
            $this->selectedMedia = null;
        }

        $this->dispatch('mediaDeleted', message: 'Media deleted successfully.');
    }
}
