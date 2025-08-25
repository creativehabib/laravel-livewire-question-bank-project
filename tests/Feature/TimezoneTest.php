<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimezoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_timezone_setting_is_applied(): void
    {
        Setting::set('timezone', 'Asia/Dhaka');

        (new AppServiceProvider($this->app))->boot();

        $this->assertEquals('Asia/Dhaka', config('app.timezone'));
        $this->assertEquals('Asia/Dhaka', date_default_timezone_get());
    }
}
