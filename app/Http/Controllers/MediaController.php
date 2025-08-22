<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');
        $mime = $file->getMimeType();
        $size = $file->getSize();
        $dimensions = str_starts_with($mime, 'image/') ? getimagesize($file->getRealPath()) : null;

        $media = Media::create([
            'name' => $file->getClientOriginalName(),
            'filename' => basename($path),
            'mime_type' => $mime,
            'path' => $path,
            'size' => $size,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Media uploaded successfully.',
            'media' => $media,
        ]);
    }
}

