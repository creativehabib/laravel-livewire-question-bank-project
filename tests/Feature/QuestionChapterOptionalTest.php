<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Subject, Question};

class QuestionChapterOptionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_chapter_is_optional_when_creating_question(): void
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Math']);

        $question = Question::create([
            'subject_id' => $subject->id,
            'chapter_id' => null,
            'title' => 'What is 2 + 2?',
            'difficulty' => 'easy',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'chapter_id' => null,
        ]);
    }
}
