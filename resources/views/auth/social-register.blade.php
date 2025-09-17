<?php

use App\Enums\Role;

?>

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">{{ __('Complete your registration') }}</h1>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('Welcome back! Please tell us if you are signing up as a student or teacher to finish creating your account.') }}
        </p>
    </div>

    <div class="mb-6 space-y-4">
        <div>
            <span class="block text-sm font-medium text-gray-700">{{ __('Name') }}</span>
            <span class="block mt-1 text-gray-900">{{ $name }}</span>
        </div>
        <div>
            <span class="block text-sm font-medium text-gray-700">{{ __('Email') }}</span>
            <span class="block mt-1 text-gray-900">{{ $email }}</span>
        </div>
        <div>
            <span class="block text-sm font-medium text-gray-700">{{ __('Signing in with') }}</span>
            <span class="block mt-1 text-gray-900">{{ ucfirst($provider) }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('social.register.complete', ['provider' => $provider]) }}" class="space-y-6">
        @csrf

        <div>
            <span class="block text-sm font-medium text-gray-700">{{ __('I am registering as') }}</span>
            <div class="mt-3 space-y-3">
                <label class="flex items-center">
                    <input
                        type="radio"
                        name="role"
                        value="{{ Role::STUDENT->value }}"
                        class="text-indigo-600 border-gray-300 focus:ring-indigo-500"
                        @checked(old('role', Role::STUDENT->value) === Role::STUDENT->value)
                    >
                    <span class="ms-2 text-sm text-gray-700">{{ __('Student') }}</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="radio"
                        name="role"
                        value="{{ Role::TEACHER->value }}"
                        class="text-indigo-600 border-gray-300 focus:ring-indigo-500"
                        @checked(old('role') === Role::TEACHER->value)
                    >
                    <span class="ms-2 text-sm text-gray-700">{{ __('Teacher') }}</span>
                </label>
            </div>
            @error('role')
                <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span>
            @enderror
        </div>

        <x-primary-button class="w-full justify-center">
            {{ __('Complete Registration') }}
        </x-primary-button>
    </form>

    <p class="mt-6 text-xs text-gray-500">
        {{ __('You will be logged in automatically after completing this step.') }}
    </p>
</x-guest-layout>
