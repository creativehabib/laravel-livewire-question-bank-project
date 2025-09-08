<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController
{
    protected array $providers = ['google', 'facebook'];

    protected function ensureEnabled(string $provider): void
    {
        abort_unless(in_array($provider, $this->providers, true), 404);

        $enabled = (bool) Setting::get("{$provider}_login_enabled", false);
        abort_unless($enabled, 404);

        config([
            "services.$provider.client_id" => Setting::get("{$provider}_client_id", config("services.$provider.client_id")),
            "services.$provider.client_secret" => Setting::get("{$provider}_client_secret", config("services.$provider.client_secret")),
            "services.$provider.redirect" => route('social.callback', $provider, absolute: true),
        ]);
    }

    public function redirect(string $provider)
    {
        $this->ensureEnabled($provider);
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $this->ensureEnabled($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', ucfirst($provider).' login failed.');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if (! $user) {
            $registrationEnabled = (bool) Setting::get('registration_enabled', true);
            if (! $registrationEnabled) {
                return redirect()->route('login')->with('status', 'Registration is disabled.');
            }

            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: $socialUser->getEmail(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'role' => Role::STUDENT,
                'email_verified_at' => now(),
                'avatar_url' => $socialUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}
