@php
    $user = auth()->user();
@endphp

<header class="sticky top-0 z-40 flex w-full items-center justify-between bg-white px-4 py-3 print:hidden sm:px-8 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="flex items-center gap-4">
        <button
            id="sidebarToggle"
            type="button"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-gray-600 transition hover:bg-gray-200 focus:outline-none md:hidden dark:text-gray-300 dark:hover:bg-gray-800"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div class="flex items-center gap-4">
        <a
            href="{{ $user->isTeacher() ? route('questions.set.create') : ($user->isAdmin() ? route('admin.questions.create') : '#') }}"
            class="{{ ($user->isTeacher() || $user->isAdmin()) ? 'flex' : 'hidden' }} items-center gap-2 rounded-full bg-[#1a1a1a] px-5 py-2 text-xs font-semibold text-white transition hover:bg-black sm:text-sm dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span class="hidden sm:inline">নতুন যোগ করুন</span>
        </a>

        <div class="relative" x-data="{ profileOpen: false }">
            <button
                @click="profileOpen = !profileOpen"
                @click.outside="profileOpen = false"
                type="button"
                class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-gray-200 text-sm font-semibold text-gray-700 transition hover:ring-2 hover:ring-gray-300 focus:outline-none"
            >
                @if ($user->avatar_url)
                    <img class="h-full w-full object-cover" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                @endif
            </button>

            <div x-show="profileOpen" x-transition x-cloak class="absolute right-0 z-20 mt-2 w-48 rounded-xl border border-gray-100 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <a wire:navigate href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700">Profile</a>
                @if(auth()->user()->isAdmin())
                    <a wire:navigate href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700">Settings</a>
                @endif
            </div>
        </div>
    </div>
</header>
