<header class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm">
    <div class="flex items-center gap-4">
        <button id="sidebarToggle" class="md:hidden text-gray-500 dark:text-gray-400 text-2xl">☰</button>
        <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative hidden md:block">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            <input type="text" placeholder="Search..."
                   class="pl-10 pr-4 py-2 rounded-lg border dark:border-gray-600 bg-gray-50 dark:bg-gray-700 w-48 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
        </div>
        <div class="relative">
            <button id="userMenuButton" class="flex items-center gap-2">
                <img class="h-9 w-9 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Admin+User&background=random" alt="Admin">
            </button>
            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-xl py-1 z-10 hidden">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Settings</a>
                <hr class="border-gray-200 dark:border-gray-600 my-1">
                <a href="#" class="block px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</a>
            </div>
        </div>
    </div>
</header>
