<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run cleanup hourly to respect retention periods configured in hours
Schedule::command('chat:clean')->hourly();
Schedule::command('chat:flush')->everyMinute();
