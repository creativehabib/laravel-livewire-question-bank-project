<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, Question $question): bool
    {
        return $user->isAdmin() || $question->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function update(User $user, Question $question): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $question->user_id === $user->id);
    }

    public function delete(User $user, Question $question): bool
    {
        return $user->isAdmin();
    }
}
