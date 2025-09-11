<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIResponseService
{
    public function generate(string $prompt, string $apiKey, string $provider = 'openai'): ?string
    {
        if ($provider === 'gemini') {
            $response = Http::post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $apiKey,
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                ]
            );

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }

            return null;
        }

        $response = Http::withToken($apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful support assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        return null;
    }
}
