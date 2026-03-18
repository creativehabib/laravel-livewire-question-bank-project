@php
    $user = auth()->user();
@endphp

<header class="print:hidden overflow-hidden rounded-[30px] border border-black/5 bg-white shadow-[0_14px_35px_-26px_rgba(15,23,42,0.55)] dark:border-white/10 dark:bg-gray-900/95">
    <div class="h-2 w-full bg-[#4b3135]"></div>

    <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">
        <div class="flex min-w-0 items-center gap-3 sm:gap-4">
            <button
                id="sidebarToggle"
                type="button"
                class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-gray-800 transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-black/10 dark:text-white dark:hover:bg-white/10"
                aria-label="Toggle sidebar menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>

            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#161616] text-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 7.75A2.75 2.75 0 0 1 5.75 5h12.5A2.75 2.75 0 0 1 21 7.75v8.5A2.75 2.75 0 0 1 18.25 19H5.75A2.75 2.75 0 0 1 3 16.25zm4.75-.25a.75.75 0 0 0-.75.75v7.5c0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75v-7.5a.75.75 0 0 0-.75-.75zm8.5 2.25a.75.75 0 0 0 0 1.5h1.25a.75.75 0 0 0 0-1.5zm-.75 3.75c0 .414.336.75.75.75h1.25a.75.75 0 0 0 0-1.5h-1.25a.75.75 0 0 0-.75.75z" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <h1 class="truncate text-base font-bold text-[#171717] dark:text-white sm:text-xl">ফাইনাল ম্যানেজার</h1>
                </div>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
            <a
                href="{{ $user->isTeacher() ? route('questions.set.create') : ($user->isAdmin() ? route('admin.questions.create') : '#') }}"
                class="{{ ($user->isTeacher() || $user->isAdmin()) ? 'inline-flex' : 'hidden' }} items-center gap-2 rounded-full bg-[#161616] px-4 py-2 text-xs font-semibold text-white transition hover:bg-black sm:px-5 sm:text-sm"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                </svg>
                <span class="hidden sm:inline">নতুন যোগ করুন</span>
                <span class="sm:hidden">যোগ</span>
            </a>

            <div class="relative">
                <button
                    id="userMenuButton"
                    type="button"
                    class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full bg-[#161616] text-sm font-semibold text-white transition hover:bg-black focus:outline-none focus:ring-2 focus:ring-black/10"
                    aria-label="Open user menu"
                >
                    @if ($user->avatar_url)
                        <img class="h-full w-full object-cover" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                    @else
                        <span>{{ $user->initials }}</span>
                    @endif
                </button>

                <div id="userMenu" class="absolute right-0 z-20 mt-3 hidden w-56 rounded-2xl border border-black/5 bg-white py-2 shadow-2xl dark:border-white/10 dark:bg-gray-800">
                    <div class="border-b border-black/5 px-4 py-3 dark:border-white/10">
                        <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Profile</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Settings</a>
                    @endif
                    <hr class="my-2 border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-left text-sm text-red-500 transition hover:bg-red-50 dark:hover:bg-red-500/10">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
