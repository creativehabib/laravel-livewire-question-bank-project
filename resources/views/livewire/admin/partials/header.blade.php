@php
    $user = auth()->user();
@endphp

<header class="sticky top-0 z-40 flex w-full items-center justify-between bg-white px-4 py-3 print:hidden sm:px-8 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700/60 transition-colors duration-300">
    <div class="flex items-center gap-4">
        <button
            id="sidebarToggle"
            type="button"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 focus:outline-none md:hidden dark:text-slate-400 dark:hover:bg-slate-700/50"
        >
            <x-heroicon-s-bars-3 class="h-6 w-6" />
        </button>
    </div>

    <div class="flex items-center gap-4">
        <a
            href="{{ $user->isTeacher() ? route('questions.set.create') : ($user->isAdmin() ? route('admin.questions.create') : '#') }}"
            class="{{ ($user->isTeacher() || $user->isAdmin()) ? 'flex' : 'hidden' }} items-center gap-2 rounded-full bg-slate-900 px-5 py-2 text-xs font-semibold text-white transition hover:bg-black sm:text-sm dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white"
        >
            <x-heroicon-s-plus class="h-4 w-4" />
            <span class="hidden sm:inline">নতুন যোগ করুন</span>
        </a>

        <div class="relative" x-data="{ profileOpen: false }">
            <button
                @click="profileOpen = !profileOpen"
                @click.outside="profileOpen = false"
                type="button"
                class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-slate-200 text-sm font-semibold text-slate-700 transition hover:ring-2 hover:ring-slate-300 focus:outline-none dark:bg-slate-700 dark:hover:ring-slate-500"
            >
                @if ($user->avatar_url)
                    <img class="h-full w-full object-cover" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                @else
                    <x-heroicon-s-user class="h-6 w-6 text-slate-500 dark:text-slate-400" />
                @endif
            </button>

            <div x-show="profileOpen" x-transition x-cloak class="absolute right-0 z-20 mt-2 w-48 rounded-xl border border-slate-100 bg-white py-1 shadow-lg dark:border-slate-700 dark:bg-slate-800">
                <a wire:navigate href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/50">Profile</a>

                @if(auth()->user()->isAdmin())
                    <a wire:navigate href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/50">Settings</a>
                @endif
            </div>
        </div>
    </div>
</header>
