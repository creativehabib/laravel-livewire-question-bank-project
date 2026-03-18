@php
    $user = auth()->user();

    $dashboardRoute = match (true) {
        $user->isAdmin() => 'admin.dashboard',
        $user->isTeacher() => 'teacher.dashboard',
        $user->isStudent() => 'student.dashboard',
        default => '#',
    };

    $questionsActive = request()->is('admin/questions*')
        || request()->is('admin/subjects*')
        || request()->is('admin/sub-subjects*')
        || request()->is('admin/chapters*')
        || request()->is('admin/tags*');

    $jobsActive = request()->is('admin/jobs*')
        || request()->is('admin/job-categories*')
        || request()->is('admin/job-companies*');

    // স্টাইলিং ক্লাসগুলো
    $primaryLinkClasses = 'nav-link flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium text-gray-500 transition-all duration-200 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white';
    $activeLinkClasses = 'bg-[#f0f2f5] text-gray-900 font-semibold dark:bg-gray-800 dark:text-white';

    $submenuPanelClasses = 'mt-1 space-y-1 pl-11 pr-4';
    $submenuLinkClasses = 'nav-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium text-gray-500 transition-all duration-200 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white';
    $submenuActiveClasses = 'text-gray-900 font-semibold dark:text-white';
@endphp

<aside id="sidebar"
       x-data="{
           isDark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
           toggleTheme() {
               this.isDark = !this.isDark;
               if (this.isDark) {
                   document.documentElement.classList.add('dark');
                   localStorage.setItem('theme', 'dark');
               } else {
                   document.documentElement.classList.remove('dark');
                   localStorage.setItem('theme', 'light');
               }
           }
       }"
       class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full transform flex-col border-r border-gray-100 bg-white transition-transform duration-300 ease-in-out print:hidden dark:border-gray-800 dark:bg-gray-900 md:translate-x-0">

    <div class="flex items-center gap-3 px-6 py-5">
        <button id="sidebarCollapse" class="hidden text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white md:block">
            <x-heroicon-s-bars-3 class="h-6 w-6" />
        </button>

        <span class="text-lg font-bold text-gray-900 dark:text-white sidebar-text">ফাইনাল ম্যানেজার</span>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5">
        <a wire:navigate href="{{ route($dashboardRoute) }}"
           class="{{ $primaryLinkClasses }} {{ request()->routeIs($dashboardRoute) ? $activeLinkClasses : '' }}">
            <x-heroicon-s-home class="h-5 w-5 flex-shrink-0"/>
            <span class="sidebar-text">Home</span>
        </a>

        @if($user->isAdmin())
            <div x-data="{ open: {{ $questionsActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full justify-between {{ $primaryLinkClasses }} {{ $questionsActive ? $activeLinkClasses : '' }}">
                    <span class="flex items-center gap-3">
                        <x-heroicon-s-document-text class="h-5 w-5 flex-shrink-0"/>
                        <span class="sidebar-text">Questions</span>
                    </span>
                    <x-heroicon-s-chevron-down class="h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }"/>
                </button>

                <div x-show="open" x-collapse x-cloak class="{{ $submenuPanelClasses }}">
                    <a wire:navigate href="{{ route('admin.questions.index') }}"
                       class="{{ $submenuLinkClasses }} {{ request()->is('admin/questions*') ? $submenuActiveClasses : '' }}">
                        <span class="sidebar-text">All Questions</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.subjects.index') }}"
                       class="{{ $submenuLinkClasses }} {{ request()->is('admin/subjects*') ? $submenuActiveClasses : '' }}">
                        <span class="sidebar-text">Subjects</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.sub-subjects.index') }}"
                       class="{{ $submenuLinkClasses }} {{ request()->is('admin/sub-subjects*') ? $submenuActiveClasses : '' }}">
                        <span class="sidebar-text">Sub Subjects</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.chapters.index') }}"
                       class="{{ $submenuLinkClasses }} {{ request()->is('admin/chapters*') ? $submenuActiveClasses : '' }}">
                        <span class="sidebar-text">Chapters</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.tags.index') }}"
                       class="{{ $submenuLinkClasses }} {{ request()->is('admin/tags*') ? $submenuActiveClasses : '' }}">
                        <span class="sidebar-text">Tags</span>
                    </a>
                </div>
            </div>
        @elseif($user->isTeacher())
            <a wire:navigate href="{{ route('teacher.questions.index') }}"
               class="{{ $primaryLinkClasses }} {{ request()->is('teacher/questions*') ? $activeLinkClasses : '' }}">
                <x-heroicon-s-document-text class="h-5 w-5 flex-shrink-0"/>
                <span class="sidebar-text">Questions</span>
            </a>
            <a wire:navigate href="{{ route('questions.set.create') }}"
               class="{{ $primaryLinkClasses }} {{ request()->routeIs('questions.set.create') ? $activeLinkClasses : '' }}">
                <x-heroicon-s-plus-circle class="h-5 w-5 flex-shrink-0"/>
                <span class="sidebar-text">প্রশ্ন ক্রিয়েট</span>
            </a>
        @elseif($user->isStudent())
            <a wire:navigate href="{{ route('student.exam') }}"
               class="{{ $primaryLinkClasses }} {{ request()->is('student/exam') ? $activeLinkClasses : '' }}">
                <x-heroicon-s-clipboard-document-check class="h-5 w-5 flex-shrink-0"/>
                <span class="sidebar-text">Exam</span>
            </a>
            <a wire:navigate href="{{ route('practice') }}"
               class="{{ $primaryLinkClasses }} {{ request()->routeIs('practice') ? $activeLinkClasses : '' }}">
                <x-heroicon-s-pencil-square class="h-5 w-5 flex-shrink-0"/>
                <span class="sidebar-text">Daily Practice</span>
            </a>
        @endif

        @if($user->isAdmin())
            <div class="my-4 border-t border-gray-100 dark:border-gray-800"></div>

            <a wire:navigate href="{{ route('admin.users.index') }}"
               class="{{ $primaryLinkClasses }} {{ request()->is('admin/users*') ? $activeLinkClasses : '' }}">
                <x-heroicon-s-users class="h-5 w-5 flex-shrink-0"/>
                <span class="sidebar-text">Users</span>
            </a>

            <div x-data="{ open: {{ $jobsActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full justify-between {{ $primaryLinkClasses }} {{ $jobsActive ? $activeLinkClasses : '' }}">
                    <span class="flex items-center gap-3">
                        <x-heroicon-s-briefcase class="h-5 w-5 flex-shrink-0"/>
                        <span class="sidebar-text">Jobs</span>
                    </span>
                    <x-heroicon-s-chevron-down class="h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }"/>
                </button>

                <div x-show="open" x-collapse x-cloak class="{{ $submenuPanelClasses }}">
                    <a wire:navigate href="{{ route('admin.jobs.index') }}"
                       class="{{ $submenuLinkClasses }} {{ request()->is('admin/jobs*') ? $submenuActiveClasses : '' }}">
                        <span class="sidebar-text">Job Posts</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.job-categories.index') }}"
                       class="{{ $submenuLinkClasses }} {{ request()->is('admin/job-categories*') ? $submenuActiveClasses : '' }}">
                        <span class="sidebar-text">Categories</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.job-companies.index') }}"
                       class="{{ $submenuLinkClasses }} {{ request()->is('admin/job-companies*') ? $submenuActiveClasses : '' }}">
                        <span class="sidebar-text">Companies</span>
                    </a>
                </div>
            </div>

            <a wire:navigate href="{{ route('admin.settings') }}"
               class="{{ $primaryLinkClasses }} {{ request()->is('admin/settings') ? $activeLinkClasses : '' }}">
                <x-heroicon-s-cog-8-tooth class="h-5 w-5 flex-shrink-0"/>
                <span class="sidebar-text">Settings</span>
            </a>
        @endif
    </nav>

    <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-800 space-y-2">

        <div data-sidebar-theme-panel class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-800/50">
            <div data-sidebar-theme-icon-group class="flex items-center gap-3">
                <x-heroicon-s-sun x-show="!isDark" class="h-5 w-5 text-gray-500" />

                <x-heroicon-s-moon x-show="isDark" x-cloak class="h-5 w-5 text-indigo-400" />

                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 sidebar-text">
                    <span x-show="!isDark">Light Mode</span>
                    <span x-show="isDark" x-cloak>Dark Mode</span>
                </span>
            </div>

            <button @click="toggleTheme()" type="button" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:bg-indigo-600" role="switch" :aria-checked="isDark.toString()">
                <span class="sr-only">Toggle dark mode</span>
                <span aria-hidden="true" :class="isDark ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
            </button>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="block">
            @csrf
            <button data-sidebar-action type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium text-red-500 transition-colors hover:bg-red-50 dark:hover:bg-red-500/10">
                <x-heroicon-s-arrow-right-start-on-rectangle class="h-5 w-5 flex-shrink-0" />
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</aside>
