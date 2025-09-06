<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIResponseService
{
    public function generate(string $prompt, string $apiKey): ?string
    {
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
