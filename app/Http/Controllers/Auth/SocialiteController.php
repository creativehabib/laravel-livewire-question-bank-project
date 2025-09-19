<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\SuspendedLoginAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController
{
    protected array $providers = ['google', 'facebook'];

    protected const REGISTRATION_SESSION_KEY = 'socialite.registration';

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

    public function callback(Request $request, string $provider)
    {
        $this->ensureEnabled($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', ucfirst($provider).' login failed.');
        }

        $email = $socialUser->getEmail();

        if (! $email) {
            return redirect()->route('login')->with('status', __('We could not retrieve an email address from your :provider account. Please use a different login method.', ['provider' => ucfirst($provider)]));
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $registrationEnabled = (bool) Setting::get('registration_enabled', true);
            if (! $registrationEnabled) {
                return redirect()->route('login')->with('status', 'Registration is disabled.');
            }

            $request->session()->put(self::REGISTRATION_SESSION_KEY, [
                'provider' => $provider,
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: $email,
                'email' => $email,
                'avatar' => $socialUser->getAvatar(),
            ]);

            return redirect()->route('social.register.show', ['provider' => $provider]);
        }

        $request->session()->forget(self::REGISTRATION_SESSION_KEY);

        if ($user->isSuspended()) {
            return $this->denySuspendedLogin($user);
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }

    public function showRegistrationForm(Request $request, string $provider): View|RedirectResponse
    {
        $this->ensureEnabled($provider);

        if (! (bool) Setting::get('registration_enabled', true)) {
            return redirect()->route('login')->with('status', 'Registration is disabled.');
        }

        $pending = $this->pendingRegistration($request, $provider);

        if (! $pending) {
            return redirect()->route('login')->with('status', __('Please start the :provider sign in process again.', ['provider' => ucfirst($provider)]));
        }

        return view('auth.social-register', [
            'provider' => $provider,
            'name' => $pending['name'],
            'email' => $pending['email'],
        ]);
    }

    public function completeRegistration(Request $request, string $provider): RedirectResponse
    {
        $this->ensureEnabled($provider);

        if (! (bool) Setting::get('registration_enabled', true)) {
            return redirect()->route('login')->with('status', 'Registration is disabled.');
        }

        $pending = $this->pendingRegistration($request, $provider);

        if (! $pending) {
            return redirect()->route('login')->with('status', __('Please start the :provider sign in process again.', ['provider' => ucfirst($provider)]));
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in([Role::TEACHER->value, Role::STUDENT->value])],
            'institution_name' => ['required_if:role,'.Role::TEACHER->value, 'string', 'max:255'],
            'division' => ['required_if:role,'.Role::TEACHER->value, 'string', 'max:255'],
            'district' => ['required_if:role,'.Role::TEACHER->value, 'string', 'max:255'],
            'thana' => ['required_if:role,'.Role::TEACHER->value, 'string', 'max:255'],
            'phone' => ['required_if:role,'.Role::TEACHER->value, 'string', 'max:30'],
            'address' => ['required_if:role,'.Role::TEACHER->value, 'string', 'max:1000'],
        ]);

        $existingUser = User::where('email', $pending['email'])->first();

        if ($existingUser) {
            $request->session()->forget(self::REGISTRATION_SESSION_KEY);

            if ($existingUser->isSuspended()) {
                return $this->denySuspendedLogin($existingUser);
            }

            Auth::login($existingUser, true);

            return redirect()->route('dashboard');
        }

        $role = Role::from($validated['role']);

        $teacherData = [
            'institution_name' => $validated['institution_name'] ?? null,
            'division' => $validated['division'] ?? null,
            'district' => $validated['district'] ?? null,
            'thana' => $validated['thana'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'teacher_profile_completed_at' => null,
        ];

        if ($role === Role::TEACHER) {
            $teacherData['teacher_profile_completed_at'] = now();
        }

        $user = User::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => Hash::make(Str::random(16)),
            'role' => $role,
            'role_confirmed_at' => now(),
            'email_verified_at' => now(),
            'avatar_url' => $pending['avatar'] ?? null,
            ...$teacherData,
        ]);

        $request->session()->forget(self::REGISTRATION_SESSION_KEY);

        if ($user->isSuspended()) {
            return $this->denySuspendedLogin($user);
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }

    protected function denySuspendedLogin(User $user): RedirectResponse
    {
        $until = $user->suspended_until;

        if ($until) {
            $user->notify(new SuspendedLoginAttempt($until));

            $timezone = config('app.timezone');
            $formattedUntil = $until->copy()->timezone($timezone)->toDayDateTimeString();
            $duration = now()->diffForHumans($until, true);
            $message = __('Your account is suspended until :date. You can log in again in :duration.', [
                'date' => $formattedUntil,
                'duration' => $duration,
            ]);
        } else {
            $message = __('Your account is currently suspended.');
        }

        return redirect()->route('login')
            ->with('status', $message);
    }

    protected function pendingRegistration(Request $request, string $provider): ?array
    {
        $pending = $request->session()->get(self::REGISTRATION_SESSION_KEY);

        if (! is_array($pending)) {
            return null;
        }

        if (($pending['provider'] ?? null) !== $provider) {
            return null;
        }

        if (empty($pending['email'])) {
            return null;
        }

        return $pending;
    }
}
