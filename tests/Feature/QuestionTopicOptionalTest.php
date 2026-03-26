<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Subject, Chapter, Question};
use Illuminate\Support\Facades\Validator;

class QuestionTopicOptionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_topic_is_optional_when_creating_question(): void
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Math']);

        $question = Question::create([
            'subject_id' => $subject->id,
            'chapter_id' => null,
            'topic_id' => null,
            'title' => 'What is 2 + 2?',
            'difficulty' => 'easy',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'chapter_id' => null,
            'topic_id' => null,
        ]);
    }

    public function test_topic_is_required_when_chapter_selected(): void
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Math']);
        $sub = Chapter::create(['subject_id' => $subject->id, 'name' => 'Algebra']);

        $validator = Validator::make([
            'subject_id' => $subject->id,
            'chapter_id' => $sub->id,
            'topic_id' => null,
            'title' => 'What is 2 + 2?',
            'difficulty' => 'easy',
            'user_id' => $user->id,
        ], [
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'topic_id' => 'required_with:chapter_id|nullable|exists:topics,id',
            'title' => 'required|string',
            'difficulty' => 'required',
            'user_id' => 'required',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('topic_id', $validator->errors()->toArray());
    }
}
