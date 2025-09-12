<aside id="sidebar"
       class="fixed inset-y-0 left-0 w-64 transform -translate-x-full md:translate-x-0
              transition-all duration-300 ease-in-out
              bg-white dark:bg-gray-800 shadow-lg flex flex-col z-50">

    {{-- Logo / Title --}}
    <div class="flex items-center justify-between p-6">
        <span class="font-bold text-xl text-indigo-600 dark:text-indigo-400 sidebar-text flex items-center gap-2">
            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
            </svg>
            <span class="sidebar-text">MCQ Bank</span>
        </span>
        <button id="sidebarCollapse" class="hidden md:inline text-gray-600 dark:text-gray-300">⏩</button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
        @php
            $dashboardRoute = match (true) {
                auth()->user()->isAdmin()   => 'admin.dashboard',
                auth()->user()->isTeacher() => 'teacher.dashboard',
                auth()->user()->isStudent() => 'student.dashboard',
                default => '#',
            };
        @endphp
        <a href="{{ route($dashboardRoute) }}"
           class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs($dashboardRoute) ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
            <x-heroicon-o-chart-bar class="w-5 h-5"/>
            <span class="sidebar-text">Dashboard</span>
        </a>

        @if(auth()->user()->isAdmin())
            @php
                $questionsActive = request()->is('admin/questions*')
                    || request()->is('admin/subjects*')
                    || request()->is('admin/sub-subjects*')
                    || request()->is('admin/chapters*')
                    || request()->is('admin/tags*');
            @endphp
            <div x-data="{ open: {{ $questionsActive ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open"
                        class="nav-link flex items-center justify-between w-full px-4 py-2.5 rounded-lg {{ $questionsActive ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                    <span class="flex items-center gap-3">
                        <x-heroicon-o-question-mark-circle class="w-5 h-5"/>
                        <span class="sidebar-text">Questions</span>
                    </span>
                    <x-heroicon-o-chevron-down
                        class="w-4 h-4 transition-transform"
                        x-bind:class="{ 'rotate-180': open }"/>
                </button>

                <div x-show="open" class="space-y-1 pl-8 mt-1" x-cloak>
                    <a wire:navigate href="{{ route('admin.questions.index') }}"
                       class="nav-link flex items-center gap-3 pr-4 pl-4 py-2.5 rounded-lg {{ request()->is('admin/questions*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                        <span class="sidebar-text">All Questions</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.subjects.index') }}"
                       class="nav-link flex items-center gap-3 pr-4 pl-4 py-2.5 rounded-lg {{ request()->is('admin/subjects*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                        <x-heroicon-o-book-open class="w-4 h-4"/>
                        <span class="sidebar-text">Subjects</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.sub-subjects.index') }}"
                       class="nav-link flex items-center gap-3 pr-4 pl-4 py-2.5 rounded-lg {{ request()->is('admin/sub-subjects*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                        <x-heroicon-o-book-open class="w-4 h-4"/>
                        <span class="sidebar-text">Sub Subjects</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.chapters.index') }}"
                       class="nav-link flex items-center gap-3 pr-4 pl-4 py-2.5 rounded-lg {{ request()->is('admin/chapters*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                        <x-heroicon-o-rectangle-stack class="w-4 h-4"/>
                        <span class="sidebar-text">Chapters</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.tags.index') }}"
                       class="nav-link flex items-center gap-3 pr-4 pl-4 py-2.5 rounded-lg {{ request()->is('admin/tags*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                        <x-heroicon-o-tag class="w-4 h-4"/>
                        <span class="sidebar-text">Tags</span>
                    </a>
                </div>
            </div>
        @elseif(auth()->user()->isTeacher())
            <a wire:navigate href="{{ route('teacher.questions.index') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->is('teacher/questions*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                <x-heroicon-o-question-mark-circle class="w-5 h-5"/>
                <span class="sidebar-text">Questions</span>
            </a>
        @endif

        @if(auth()->user()->isAdmin())
            <a wire:navigate href="{{ route('admin.settings') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->is('admin/settings') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                <x-heroicon-o-cog-6-tooth class="w-5 h-5"/>
                <span class="sidebar-text">Settings</span>
            </a>

            <a wire:navigate href="{{ route('admin.users.index') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->is('admin/users*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                <x-heroicon-o-user-group class="w-5 h-5"/>
                <span class="sidebar-text">Users</span>
            </a>

            @php
                $jobsActive = request()->is('admin/jobs*') || request()->is('admin/job-categories*') || request()->is('admin/job-companies*');
            @endphp
            <div x-data="{ open: {{ $jobsActive ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open"
                        class="nav-link flex items-center justify-between w-full px-4 py-2.5 rounded-lg {{ $jobsActive ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                    <span class="flex items-center gap-3">
                        <x-heroicon-o-briefcase class="w-5 h-5"/>
                        <span class="sidebar-text">Jobs</span>
                    </span>
                    <x-heroicon-o-chevron-down class="w-4 h-4 transition-transform" x-bind:class="{ 'rotate-180': open }"/>
                </button>

                <div x-show="open" class="space-y-1 pl-8 mt-1" x-cloak>
                    <a wire:navigate href="{{ route('admin.jobs.index') }}"
                       class="nav-link flex items-center gap-3 pr-4 pl-4 py-2.5 rounded-lg {{ request()->is('admin/jobs*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                        <span class="sidebar-text">Job Posts</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.job-categories.index') }}"
                       class="nav-link flex items-center gap-3 pr-4 pl-4 py-2.5 rounded-lg {{ request()->is('admin/job-categories*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                        <span class="sidebar-text">Categories</span>
                    </a>
                    <a wire:navigate href="{{ route('admin.job-companies.index') }}"
                       class="nav-link flex items-center gap-3 pr-4 pl-4 py-2.5 rounded-lg {{ request()->is('admin/job-companies*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                        <span class="sidebar-text">Companies</span>
                    </a>
                </div>
            </div>

            <a wire:navigate href="{{ route('admin.media.index') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->is('admin/media*') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                <x-heroicon-o-photo class="w-5 h-5"/>
                <span class="sidebar-text">Media</span>
            </a>
        @endif
    </nav>

    {{-- Dark Mode Toggle --}}
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
        <div class="flex items-center justify-between">
            <span class="sidebar-text text-sm font-medium">Dark Mode</span>
            <label for="darkToggle" class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="darkToggle" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-600
                            peer-checked:after:translate-x-full peer-checked:after:border-white
                            after:content-[''] after:absolute after:top-0.5 after:left-[2px]
                            after:bg-white after:border-gray-300 after:border after:rounded-full
                            after:h-5 after:w-5 after:transition-all dark:border-gray-500
                            peer-checked:bg-indigo-600"></div>
            </label>
        </div>
    </div>
</aside>
