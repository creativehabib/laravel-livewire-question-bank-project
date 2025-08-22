<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\User;
use App\Services\QuestionViewService;
use Illuminate\Support\Facades\Cache;

class QuestionViewServiceTest extends TestCase
{
    public function test_it_counts_unique_views_per_ip(): void
    {
        Cache::flush();

        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Math']);
        $chapter = Chapter::create(['subject_id' => $subject->id, 'name' => 'Algebra']);
        $question = Question::create([
            'subject_id' => $subject->id,
            'chapter_id' => $chapter->id,
            'title' => '1 + 1 = ?',
            'difficulty' => 'easy',
            'slug' => '1-plus-1',
            'user_id' => $user->id,
        ]);

        $service = new QuestionViewService();

        $service->record($question, '127.0.0.1');
        $service->record($question, '127.0.0.1');
        $service->record($question, '127.0.0.2');

        $this->assertSame(2, $question->fresh()->views);
    }
}
