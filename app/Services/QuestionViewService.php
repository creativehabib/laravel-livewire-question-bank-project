<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Facades\Cache;

class QuestionViewService
{
    /**
     * Record a unique view for a question by IP address.
     */
    public function record(Question $question, string $ip): void
    {
        $key = "question:{$question->id}:viewed:{$ip}";

        if (Cache::add($key, true, now()->addDay())) {
            $question->increment('views');
        }
    }
}
