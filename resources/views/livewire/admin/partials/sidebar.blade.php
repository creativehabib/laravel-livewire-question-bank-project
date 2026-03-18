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

    $primaryLinkClasses = 'nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-[15px] font-medium text-gray-700 transition-all duration-200 hover:bg-[#eceef1] hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-800 dark:hover:text-white';
    $secondaryLinkClasses = 'nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-[15px] font-medium text-gray-700 transition-all duration-200 hover:bg-[#eceef1] hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-800 dark:hover:text-white';
    $activeLinkClasses = 'bg-[#dfe3e8] text-gray-950 shadow-[inset_0_1px_0_rgba(255,255,255,0.8)] dark:bg-gray-800 dark:text-white';
    $submenuPanelClasses = 'mt-2 space-y-1 rounded-[22px] bg-[#f5f6f8] p-2 dark:bg-gray-900/80';
    $submenuLinkClasses = 'nav-link flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium text-gray-600 transition-all duration-200 hover:bg-white hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white';
    $submenuActiveClasses = 'bg-white text-gray-950 shadow-sm dark:bg-gray-800 dark:text-white';
@endphp

<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full transform flex-col border-r border-black/5 bg-white transition-all duration-300 ease-in-out print:hidden dark:border-white/10 dark:bg-gray-900 md:translate-x-0">

    <div class="flex items-center gap-3 border-b border-[#eceef1] px-5 py-4 dark:border-gray-800">
        <button id="sidebarCollapse" class="hidden h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-gray-700 shadow-sm transition hover:bg-[#f3f4f6] dark:border-white/10 dark:bg-gray-800 dark:text-gray-200 md:inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>

        <div class="flex min-w-0 items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#111827] text-white shadow-sm dark:bg-white dark:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 7.75A2.75 2.75 0 0 1 5.75 5h12.5A2.75 2.75 0 0 1 21 7.75v8.5A2.75 2.75 0 0 1 18.25 19H5.75A2.75 2.75 0 0 1 3 16.25zm4.75-.25a.75.75 0 0 0-.75.75v7.5c0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75v-7.5a.75.75 0 0 0-.75-.75zm8.5 2.25a.75.75 0 0 0 0 1.5h1.25a.75.75 0 0 0 0-1.5zm-.75 3.75c0 .414.336.75.75.75h1.25a.75.75 0 0 0 0-1.5h-1.25a.75.75 0 0 0-.75.75z" />
                </svg>
            </div>

            <div class="min-w-0 sidebar-text">
                <p class="truncate text-lg font-bold text-gray-950 dark:text-white">ফাইনাল ম্যানেজার</p>
                <p class="truncate text-xs font-medium tracking-[0.25em] text-gray-400 dark:text-gray-500">QUESTION BANK</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="space-y-1">
            <a href="{{ route($dashboardRoute) }}"
               class="{{ $primaryLinkClasses }} {{ request()->routeIs($dashboardRoute) ? $activeLinkClasses : '' }}">
                <x-heroicon-s-home class="h-5 w-5 flex-shrink-0"/>
                <span class="sidebar-text">হোম</span>
            </a>

            @if($user->isAdmin())
                <div x-data="{ open: {{ $questionsActive ? 'true' : 'false' }} }" class="submenu space-y-1">
                    <button @click="open = !open"
                            class="submenu-toggle {{ $secondaryLinkClasses }} w-full justify-between {{ $questionsActive ? $activeLinkClasses : '' }}">
                        <span class="flex items-center gap-3">
                            <x-heroicon-s-question-mark-circle class="h-5 w-5 flex-shrink-0"/>
                            <span class="sidebar-text">Questions</span>
                        </span>
                        <x-heroicon-s-chevron-down class="arrow h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }"/>
                    </button>

                    <div x-show="open" x-cloak class="submenu-items {{ $submenuPanelClasses }}">
                        <a wire:navigate href="{{ route('admin.questions.index') }}"
                           class="{{ $submenuLinkClasses }} {{ request()->is('admin/questions*') ? $submenuActiveClasses : '' }}">
                            <span class="sidebar-text">All Questions</span>
                        </a>
                        <a wire:navigate href="{{ route('admin.subjects.index') }}"
                           class="{{ $submenuLinkClasses }} {{ request()->is('admin/subjects*') ? $submenuActiveClasses : '' }}">
                            <x-heroicon-s-book-open class="h-4 w-4 flex-shrink-0"/>
                            <span class="sidebar-text">Subjects</span>
                        </a>
                        <a wire:navigate href="{{ route('admin.sub-subjects.index') }}"
                           class="{{ $submenuLinkClasses }} {{ request()->is('admin/sub-subjects*') ? $submenuActiveClasses : '' }}">
                            <x-heroicon-s-book-open class="h-4 w-4 flex-shrink-0"/>
                            <span class="sidebar-text">Sub Subjects</span>
                        </a>
                        <a wire:navigate href="{{ route('admin.chapters.index') }}"
                           class="{{ $submenuLinkClasses }} {{ request()->is('admin/chapters*') ? $submenuActiveClasses : '' }}">
                            <x-heroicon-s-rectangle-stack class="h-4 w-4 flex-shrink-0"/>
                            <span class="sidebar-text">Chapters</span>
                        </a>
                        <a wire:navigate href="{{ route('admin.tags.index') }}"
                           class="{{ $submenuLinkClasses }} {{ request()->is('admin/tags*') ? $submenuActiveClasses : '' }}">
                            <x-heroicon-s-tag class="h-4 w-4 flex-shrink-0"/>
                            <span class="sidebar-text">Tags</span>
                        </a>
                    </div>
                </div>
            @elseif($user->isTeacher())
                <a wire:navigate href="{{ route('teacher.questions.index') }}"
                   class="{{ $primaryLinkClasses }} {{ request()->is('teacher/questions*') ? $activeLinkClasses : '' }}">
                    <x-heroicon-s-question-mark-circle class="h-5 w-5 flex-shrink-0"/>
                    <span class="sidebar-text">Questions</span>
                </a>
                <a wire:navigate href="{{ route('questions.set.create') }}"
                   class="{{ $primaryLinkClasses }} {{ request()->routeIs('questions.set.create') ? $activeLinkClasses : '' }}">
                    <x-heroicon-s-sparkles class="h-5 w-5 flex-shrink-0"/>
                    <span class="sidebar-text">প্রশ্ন ক্রিয়েট</span>
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
        </div>

        @if($user->isAdmin())
            <div class="my-4 border-t border-[#eceef1] dark:border-gray-800"></div>

            <div class="space-y-1">
                <a wire:navigate href="{{ route('admin.settings') }}"
                   class="{{ $primaryLinkClasses }} {{ request()->is('admin/settings') ? $activeLinkClasses : '' }}">
                    <x-heroicon-s-cog-6-tooth class="h-5 w-5 flex-shrink-0"/>
                    <span class="sidebar-text">Settings</span>
                </a>

                <a wire:navigate href="{{ route('admin.users.index') }}"
                   class="{{ $primaryLinkClasses }} {{ request()->is('admin/users*') ? $activeLinkClasses : '' }}">
                    <x-heroicon-s-user-group class="h-5 w-5 flex-shrink-0"/>
                    <span class="sidebar-text">Users</span>
                </a>

                <div x-data="{ open: {{ $jobsActive ? 'true' : 'false' }} }" class="submenu space-y-1">
                    <button @click="open = !open"
                            class="submenu-toggle {{ $secondaryLinkClasses }} w-full justify-between {{ $jobsActive ? $activeLinkClasses : '' }}">
                        <span class="flex items-center gap-3">
                            <x-heroicon-s-briefcase class="h-5 w-5 flex-shrink-0"/>
                            <span class="sidebar-text">Jobs</span>
                        </span>
                        <x-heroicon-s-chevron-down class="arrow h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }"/>
                    </button>

                    <div x-show="open" x-cloak class="submenu-items {{ $submenuPanelClasses }}">
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

                <a wire:navigate href="{{ route('admin.media.index') }}"
                   class="{{ $primaryLinkClasses }} {{ request()->is('admin/media*') ? $activeLinkClasses : '' }}">
                    <x-heroicon-s-photo class="h-5 w-5 flex-shrink-0"/>
                    <span class="sidebar-text">Media</span>
                </a>
            </div>
        @endif
    </nav>

    <div class="border-t border-[#eceef1] p-4 dark:border-gray-800">
        <div class="rounded-[24px] bg-[#f5f6f8] p-4 dark:bg-gray-800/80">
            <div class="flex items-center justify-between gap-3">
                <div class="sidebar-text">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Dark Mode</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">সিস্টেম থিম বদলান</p>
                </div>

                <label for="darkToggle" class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" id="darkToggle" class="peer sr-only">
                    <div class="h-6 w-11 rounded-full bg-gray-300 transition peer-checked:bg-gray-900 peer-focus:outline-none dark:bg-gray-600 dark:peer-checked:bg-white"></div>
                    <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white transition peer-checked:translate-x-5 dark:bg-gray-900 dark:peer-checked:bg-gray-900"></div>
                </label>
            </div>
        </div>
    </div>
</aside>
