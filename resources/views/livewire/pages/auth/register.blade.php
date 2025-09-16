<?php

use App\Enums\Role;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?string $selectedRole = null;
    public bool $googleLogin = false;
    public bool $facebookLogin = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'selectedRole' => ['required', Rule::in([Role::TEACHER->value, Role::STUDENT->value])],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $role = Role::from($validated['selectedRole']);

        unset($validated['selectedRole']);

        $validated['role'] = $role;
        $validated['role_confirmed_at'] = now();

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function mount(): void
    {
        $this->googleLogin = (bool) Setting::get('google_login_enabled', false);
        $this->facebookLogin = (bool) Setting::get('facebook_login_enabled', false);
    }
}; ?>

<div>
    @if ($googleLogin || $facebookLogin)
        <div class="mb-4 space-y-2">
            @if ($googleLogin)
                <a href="{{ route('social.redirect', 'google') }}" class="w-full flex items-center justify-center px-4 py-2 border rounded">
                    <svg class="w-5 h-5" viewBox="0 0 488 512" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#EA4335" d="M488 261.8c0-18.5-1.5-37.1-4.7-55.1H249v104.4h134.6c-5.8 31.3-23.4 57.9-50 75.5v62.7h80.7c47.2-43.5 74.7-107.7 74.7-187.5z"/>
                        <path fill="#34A853" d="M249 512c67.1 0 123.7-22.1 164.9-60l-80.7-62.7c-22.4 15-50.8 23.8-84.2 23.8-64.8 0-119.7-43.8-139.5-102.5H27.1v64.5C68.7 455.9 153.6 512 249 512z"/>
                        <path fill="#4A90E2" d="M109.5 309.6c-4.9-15-7.7-31-7.7-47.6s2.8-32.6 7.7-47.6v-64.5H27.1C9.7 186.6 0 218.5 0 261.8s9.7 75.2 27.1 111.9l82.4-64.1z"/>
                        <path fill="#FBBC05" d="M249 102.1c36.5 0 69.2 12.6 94.9 33.7l71.3-71.3C372.4 24.6 316.1 0 249 0 153.6 0 68.7 56.1 27.1 150.4l82.4 64.1c19.8-58.7 74.7-102.4 139.5-102.4z"/>
                    </svg>
                    <span class="ml-2">{{ __('Register with Google') }}</span>
                </a>
            @endif
            @if ($facebookLogin)
                <a href="{{ route('social.redirect', 'facebook') }}" class="w-full flex items-center justify-center px-4 py-2 border rounded">
                    <svg class="w-5 h-5" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#1877F2" d="M279.14 288l14.22-92.66h-88.91V127.35c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                    </svg>
                    <span class="ml-2">{{ __('Register with Facebook') }}</span>
                </a>
            @endif
        </div>
    @endif
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label :value="__('Register as')" />
            <div class="mt-2 space-y-2">
                <label class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition hover:border-indigo-500/50 hover:bg-indigo-50 dark:border-gray-700 dark:hover:border-indigo-400 dark:hover:bg-indigo-500/10">
                    <input type="radio" wire:model="selectedRole" value="teacher" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Teacher') }}</span>
                </label>

                <label class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition hover:border-indigo-500/50 hover:bg-indigo-50 dark:border-gray-700 dark:hover:border-indigo-400 dark:hover:bg-indigo-500/10">
                    <input type="radio" wire:model="selectedRole" value="student" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Student') }}</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('selectedRole')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
