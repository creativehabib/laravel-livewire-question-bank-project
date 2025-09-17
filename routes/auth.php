<?php

use App\Http\Controllers\Auth\EmailLoginController;
use App\Http\Controllers\Auth\EmailRegistrationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Livewire\Actions\Logout;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    if (Setting::get('registration_enabled', true)) {
        Volt::route('register', 'pages.auth.register')
            ->name('register');
    }

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');

    Route::get('auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
    Route::get('auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');
    Route::get('auth/{provider}/register', [SocialiteController::class, 'showRegistrationForm'])->name('social.register.show');
    Route::post('auth/{provider}/register', [SocialiteController::class, 'completeRegistration'])->name('social.register.complete');

    Route::get('email-login/{user}', EmailLoginController::class)
        ->middleware('signed')
        ->name('auth.email-login');

    Route::get('email-register', EmailRegistrationController::class)
        ->middleware('signed')
        ->name('auth.email-register');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    Route::post('logout', function (Logout $logout) {
        $logout();
        return redirect()->route('landing');
    })->name('logout');
});
