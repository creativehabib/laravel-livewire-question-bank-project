<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\CleanOldChatMessages;
use App\Console\Commands\FlushChatCache;
use App\Console\Commands\ProcessCachedMessages;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withProviders([
        \App\Providers\AppServiceProvider::class,
        \App\Providers\AuthServiceProvider::class,
        \App\Providers\VoltServiceProvider::class,
    ])
    ->withCommands([
        CleanOldChatMessages::class,
        FlushChatCache::class,
        ProcessCachedMessages::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
