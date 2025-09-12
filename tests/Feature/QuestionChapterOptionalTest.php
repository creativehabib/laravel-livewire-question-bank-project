<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Subject, SubSubject, Question};
use Illuminate\Support\Facades\Validator;

class QuestionChapterOptionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_chapter_is_optional_when_creating_question(): void
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Math']);

        $question = Question::create([
            'subject_id' => $subject->id,
            'sub_subject_id' => null,
            'chapter_id' => null,
            'title' => 'What is 2 + 2?',
            'difficulty' => 'easy',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'sub_subject_id' => null,
            'chapter_id' => null,
        ]);
    }

    public function test_chapter_is_required_when_sub_subject_selected(): void
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Math']);
        $sub = SubSubject::create(['subject_id' => $subject->id, 'name' => 'Algebra']);

        $validator = Validator::make([
            'subject_id' => $subject->id,
            'sub_subject_id' => $sub->id,
            'chapter_id' => null,
            'title' => 'What is 2 + 2?',
            'difficulty' => 'easy',
            'user_id' => $user->id,
        ], [
            'subject_id' => 'required|exists:subjects,id',
            'sub_subject_id' => 'nullable|exists:sub_subjects,id',
            'chapter_id' => 'required_with:sub_subject_id|nullable|exists:chapters,id',
            'title' => 'required|string',
            'difficulty' => 'required',
            'user_id' => 'required',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('chapter_id', $validator->errors()->toArray());
    }
}
