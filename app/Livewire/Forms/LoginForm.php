<?php

namespace App\Livewire\Forms;

use App\Models\User;
use App\Notifications\SuspendedLoginAttempt;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        $user = Auth::user();

        if ($user instanceof User && $user->isSuspended()) {
            $this->handleSuspendedUser($user);
        }

        RateLimiter::clear($this->throttleKey());
    }

    protected function handleSuspendedUser(User $user): void
    {
        $until = $user->suspended_until;

        Auth::logout();
        RateLimiter::clear($this->throttleKey());

        if ($until) {
            $user->notify(new SuspendedLoginAttempt($until));

            $timezone = config('app.timezone');
            $formattedUntil = $until->copy()->timezone($timezone)->toDayDateTimeString();
            $duration = now()->diffForHumans($until, true);

            throw ValidationException::withMessages([
                'form.email' => __('Your account is suspended until :date. You can log in again in :duration.', [
                    'date' => $formattedUntil,
                    'duration' => $duration,
                ]),
            ]);
        }

        throw ValidationException::withMessages([
            'form.email' => __('Your account is currently suspended.'),
        ]);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
