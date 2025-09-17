<div>
    @if ($showRoleModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/80 px-4">
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Choose account type') }}</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ __('Please select whether you want to use the platform as a teacher or a student.') }}
                </p>

                <form wire:submit.prevent="submitRole" class="mt-6 space-y-4">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition hover:border-indigo-500 hover:bg-indigo-50 dark:border-gray-700 dark:hover:border-indigo-400 dark:hover:bg-indigo-500/10">
                        <input type="radio" wire:model="selectedRole" value="teacher" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Teacher') }}</span>
                    </label>

                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition hover:border-indigo-500 hover:bg-indigo-50 dark:border-gray-700 dark:hover:border-indigo-400 dark:hover:bg-indigo-500/10">
                        <input type="radio" wire:model="selectedRole" value="student" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Student') }}</span>
                    </label>

                    <x-input-error :messages="$errors->get('selectedRole')" />

                    <x-primary-button class="w-full justify-center">{{ __('Continue') }}</x-primary-button>
                </form>
            </div>
        </div>
    @endif

    @if ($showTeacherForm)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/80 px-4 py-6">
            <div class="w-full max-w-3xl overflow-y-auto rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800" style="max-height: 90vh;">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Complete teacher profile') }}</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ __('Provide the following information to finish setting up your teacher account.') }}
                </p>

                <form wire:submit.prevent="submitTeacherForm" class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="col-span-1">
                        <x-input-label for="teacher-institution-name" :value="__('Institution Name')" />
                        <x-text-input id="teacher-institution-name" type="text" wire:model.defer="institution_name" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('institution_name')" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                        <x-input-label for="teacher-division" :value="__('Division')" />
                        <x-text-input id="teacher-division" type="text" wire:model.defer="division" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('division')" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                        <x-input-label for="teacher-district" :value="__('District')" />
                        <x-text-input id="teacher-district" type="text" wire:model.defer="district" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('district')" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                        <x-input-label for="teacher-thana" :value="__('Thana')" />
                        <x-text-input id="teacher-thana" type="text" wire:model.defer="thana" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('thana')" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                        <x-input-label for="teacher-phone" :value="__('Mobile Number')" />
                        <x-text-input id="teacher-phone" type="text" wire:model.defer="phone" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <x-input-label for="teacher-address" :value="__('Address')" />
                        <textarea id="teacher-address" wire:model.defer="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"></textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div class="col-span-1 md:col-span-2 flex justify-end">
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
