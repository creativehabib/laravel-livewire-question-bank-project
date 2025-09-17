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

        <div id="teacher-fields" class="space-y-4 hidden">
            <div>
                <label for="institution_name" class="block text-sm font-medium text-gray-700">{{ __('Institution Name') }}</label>
                <input
                    type="text"
                    id="institution_name"
                    name="institution_name"
                    value="{{ old('institution_name') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                @error('institution_name')
                    <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="division" class="block text-sm font-medium text-gray-700">{{ __('Division') }}</label>
                    <input
                        type="text"
                        id="division"
                        name="division"
                        value="{{ old('division') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('division')
                        <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700">{{ __('District') }}</label>
                    <input
                        type="text"
                        id="district"
                        name="district"
                        value="{{ old('district') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('district')
                        <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="thana" class="block text-sm font-medium text-gray-700">{{ __('Upazila/Thana') }}</label>
                    <input
                        type="text"
                        id="thana"
                        name="thana"
                        value="{{ old('thana') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('thana')
                        <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Mobile Number') }}</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('phone')
                        <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">{{ __('Address') }}</label>
                <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                @error('address')
                    <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <x-primary-button class="w-full justify-center">
            {{ __('Complete Registration') }}
        </x-primary-button>
    </form>

    <p class="mt-6 text-xs text-gray-500">
        {{ __('You will be logged in automatically after completing this step.') }}
    </p>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roleInputs = Array.from(document.querySelectorAll('input[name="role"]'));
            const teacherFields = document.getElementById('teacher-fields');

            if (!teacherFields || roleInputs.length === 0) {
                return;
            }

            const toggleTeacherFields = () => {
                const selectedRole = roleInputs.find(input => input.checked)?.value;

                if (selectedRole === '{{ Role::TEACHER->value }}') {
                    teacherFields.classList.remove('hidden');
                } else {
                    teacherFields.classList.add('hidden');
                }
            };

            roleInputs.forEach(input => {
                input.addEventListener('change', toggleTeacherFields);
            });

            toggleTeacherFields();
        });
    </script>
</x-guest-layout>
