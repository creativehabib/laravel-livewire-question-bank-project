@php
    $user = auth()->user();
@endphp

<header class="app-panel sticky top-0 z-40 mx-3 mt-3 flex w-auto items-center justify-between rounded-[1.5rem] border px-4 py-3 print:hidden sm:mx-6 sm:px-6 lg:mx-8 lg:px-8">
    <div class="flex items-center gap-4">
        <button
            id="sidebarToggle"
            type="button"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-indigo-500/10 hover:text-indigo-600 focus:outline-none dark:text-slate-300 dark:hover:bg-indigo-500/15 dark:hover:text-indigo-200 md:hidden"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div class="flex items-center gap-4">
        <a
            href="{{ $user->isTeacher() ? route('questions.set.create') : ($user->isAdmin() ? route('admin.questions.create') : '#') }}"
            class="{{ ($user->isTeacher() || $user->isAdmin()) ? 'flex' : 'hidden' }} items-center gap-2 rounded-full bg-indigo-600 px-5 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:bg-indigo-700 sm:text-sm dark:bg-indigo-400 dark:text-slate-950 dark:hover:bg-indigo-300"
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
                class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-slate-200/80 text-sm font-semibold text-slate-700 transition hover:ring-2 hover:ring-indigo-200 focus:outline-none dark:bg-slate-800 dark:text-slate-100 dark:hover:ring-indigo-500/30"
            >
                @if ($user->avatar_url)
                    <img class="h-full w-full object-cover" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-500 dark:text-slate-300" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                @endif
            </button>

            <div x-show="profileOpen" x-transition x-cloak class="app-panel absolute right-0 z-20 mt-3 w-48 rounded-2xl py-1">
                <a wire:navigate href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-500/10 dark:text-slate-200 dark:hover:bg-indigo-500/10">Profile</a>
                @if(auth()->user()->isAdmin())
                    <a wire:navigate href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-500/10 dark:text-slate-200 dark:hover:bg-indigo-500/10">Settings</a>
                @endif
            </div>
        </div>
    </div>
</header>
