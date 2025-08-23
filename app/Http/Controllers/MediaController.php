<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240', // 10MB Max
            'url' => 'nullable|url',
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('media', 'public');
                $mime = $file->getMimeType();
                $size = $file->getSize();
                $dimensions = str_starts_with($mime, 'image/') ? getimagesize($file->getRealPath()) : [null, null];
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            } elseif ($request->filled('url')) {
                $url = $request->input('url');

                $response = Http::timeout(30)->get($url);

                if (! $response->successful()) {
                    return response()->json(['message' => 'Provided URL is not accessible or invalid.'], 422);
                }

                $content = $response->body();
                $mime = $response->header('Content-Type') ?? 'application/octet-stream';
                $size = strlen($content);

                $extension = Str::afterLast(parse_url($url, PHP_URL_PATH), '.');
                if (!$extension || strlen($extension) > 5) { // Basic validation for extension
                    $parts = explode('/', $mime);
                    $extension = end($parts) ?? 'tmp';
                }

                $filename = Str::random(40) . '.' . $extension;
                $path = 'media/' . $filename;

                Storage::disk('public')->put($path, $content);

                $dimensions = str_starts_with($mime, 'image/') ? getimagesizefromstring($content) : [null, null];
                $originalName = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);

            } else {
                return response()->json(['message' => 'No file or URL provided.'], 422);
            }

            Media::create([
                'name' => $originalName,
                'filename' => basename($path),
                'mime_type' => $mime,
                'path' => $path,
                'disk' => 'public',
                'size' => $size,
                'width' => $dimensions[0] ?? null,
                'height' => $dimensions[1] ?? null,
                'created_by' => auth()->id(),
            ]);

            return response()->json(['message' => 'Media uploaded successfully.']);

        } catch (Throwable $e) {
            logger()->error('Media Upload Failed: '.$e->getMessage());
            return response()->json(['message' => 'An error occurred during upload. Please check the file type and URL.'], 500);
        }
    }
}
