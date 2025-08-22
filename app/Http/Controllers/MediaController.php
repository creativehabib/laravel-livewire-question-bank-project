<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'nullable|file|max:10240',
            'url' => 'nullable|url',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('media', 'public');
            $mime = $file->getMimeType();
            $size = $file->getSize();
            $dimensions = str_starts_with($mime, 'image/') ? getimagesize($file->getRealPath()) : null;
            $originalName = $file->getClientOriginalName();
        } elseif ($request->filled('url')) {
            $response = Http::get($request->input('url'));
            if (! $response->successful()) {
                return response()->json(['message' => 'Unable to download file.'], 422);
            }

            $content = $response->body();
            $mime = $response->header('Content-Type', 'application/octet-stream');
            $size = strlen($content);
            $filename = basename(parse_url($request->input('url'), PHP_URL_PATH)) ?: 'file';
            $path = 'media/' . Str::random(40) . '-' . $filename;
            Storage::disk('public')->put($path, $content);
            $dimensions = str_starts_with($mime, 'image/') ? getimagesizefromstring($content) : null;
            $originalName = $filename;
        } else {
            return response()->json(['message' => 'No file or url provided.'], 422);
        }

        $media = Media::create([
            'name' => $originalName,
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

