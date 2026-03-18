<header class="flex print:hidden items-center justify-between rounded-[26px] border border-black/5 bg-white px-4 py-3 shadow-[0_18px_45px_-32px_rgba(15,23,42,0.55)] dark:border-white/10 dark:bg-gray-900/95 sm:px-6">
    <div class="flex items-center print:hidden gap-3">
        <button id="sidebarToggle" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-gray-700 shadow-sm transition hover:bg-gray-100 dark:border-white/10 dark:bg-gray-800 dark:text-gray-200 md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-gray-400 dark:text-gray-500">Dashboard</p>
            <h1 class="text-lg font-bold text-gray-900 dark:text-white md:text-2xl">কন্ট্রোল প্যানেল</h1>
        </div>
    </div>
    <div class="flex items-center gap-3 print:hidden">
        <div class="relative hidden lg:block">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            <input type="text" placeholder="Search..."
                   class="w-64 rounded-2xl border border-gray-200 bg-[#f5f6f8] py-3 pl-11 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-gray-300 focus:ring-2 focus:ring-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:ring-gray-700">
        </div>
        <div class="relative">
            <button id="userMenuButton" class="flex items-center gap-3 rounded-2xl border border-black/5 bg-[#f7f7f8] px-2.5 py-2 shadow-sm transition hover:bg-gray-100 dark:border-white/10 dark:bg-gray-800 dark:hover:bg-gray-700">
                @if (auth()->user()->avatar_url)
                    <img class="h-10 w-10 rounded-2xl object-cover" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                @else
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gray-200 text-sm font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-100">
                        {{ auth()->user()->initials }}
                    </span>
                @endif
                <span class="hidden text-left sm:block">
                    <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth()->user()->role ?? 'user') }}</span>
                </span>
            </button>
            <div id="userMenu" class="absolute right-0 z-10 mt-3 hidden w-52 rounded-2xl border border-black/5 bg-white py-2 shadow-2xl dark:border-white/10 dark:bg-gray-800">
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
</header>
