<?php

namespace App\Providers;

use App\Models\Question;
use App\Policies\QuestionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Question::class => QuestionPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
