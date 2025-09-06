<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiResponseService
{
    public function generate(string $prompt, string $apiKey): ?string
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
        $response = Http::post($url.'?key='.$apiKey, [
            'contents' => [
                ['parts' => [['text' => $prompt]]],
            ],
        ]);

        if ($response->successful()) {
            return $response->json('candidates.0.content.parts.0.text');
        }

        return null;
    }
}
