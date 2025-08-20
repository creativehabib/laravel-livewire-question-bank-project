<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <title>Premium Dashboard</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="flex bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased">

{{-- ✅ Sidebar --}}
<aside id="sidebar"
       class="fixed inset-y-0 left-0 w-64 transform -translate-x-full md:translate-x-0
              transition-all duration-300 ease-in-out
              bg-white dark:bg-gray-800 shadow-lg flex flex-col z-50">
    <div class="flex items-center justify-between p-6">
        <span class="font-bold text-xl text-indigo-600 dark:text-indigo-400 sidebar-text flex items-center gap-2">
            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" /></svg>
            <span class="sidebar-text">MCQ Bank</span>
        </span>
        <button id="sidebarCollapse" class="hidden md:inline text-gray-600 dark:text-gray-300">⏩</button>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
        <a href="/admin/dashboard" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" /></svg>
            <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="/admin/questions" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" /></svg>
            <span class="sidebar-text">Questions</span>
        </a>

        <div class="submenu relative">
            <button class="submenu-toggle nav-link flex items-center justify-between w-full px-4 py-2.5 rounded-lg">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                    <span class="sidebar-text">Subjects</span>
                </span>
                <svg class="w-4 h-4 transition-transform arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="submenu-items ml-8 mt-1 space-y-1 overflow-hidden transition-[max-height] duration-500 ease-in-out max-h-0">
                <a href="/admin/subjects/math" class="nav-link block px-3 py-2 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Math</a>
                <a href="/admin/subjects/physics" class="nav-link block px-3 py-2 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Physics</a>
                <a href="/admin/subjects/biology" class="nav-link block px-3 py-2 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Biology</a>
                <a href="/dashboard" class="nav-link block px-3 py-2 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</a>
            </div>
        </div>

        <a href="/admin/students" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
            <span class="sidebar-text">Students</span>
        </a>
    </nav>

    <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
        <div class="flex items-center justify-between">
            <span class="sidebar-text text-sm font-medium">Dark Mode</span>
            <label for="darkToggle" class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="darkToggle" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-indigo-600"></div>
            </label>
        </div>
    </div>
</aside>

{{-- ✅ Main Content --}}
<div id="mainContent" class="flex-1 md:ml-64 p-4 md:p-8 space-y-6 transition-all duration-200">

    {{-- Topbar --}}
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
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Settings</a>
                    <hr class="border-gray-200 dark:border-gray-600 my-1">
                    <a href="#" class="block px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</a>
                </div>
            </div>
        </div>
    </header>

    {{-- ✅ Upgraded Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm flex items-center justify-between hover:shadow-lg hover:-translate-y-1 transition-all">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Questions</h3>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">12,340</p>
            </div>
            <div class="p-3 bg-indigo-100 dark:bg-indigo-500/20 rounded-full">
                <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm flex items-center justify-between hover:shadow-lg hover:-translate-y-1 transition-all">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Subjects</h3>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">35</p>
            </div>
            <div class="p-3 bg-emerald-100 dark:bg-emerald-500/20 rounded-full">
                <svg class="w-6 h-6 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
            </div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm flex items-center justify-between hover:shadow-lg hover:-translate-y-1 transition-all">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Students</h3>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">2,140</p>
            </div>
            <div class="p-3 bg-sky-100 dark:bg-sky-500/20 rounded-full">
                <svg class="w-6 h-6 text-sky-500 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm flex items-center justify-between hover:shadow-lg hover:-translate-y-1 transition-all">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">New Today</h3>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">+45</p>
            </div>
            <div class="p-3 bg-orange-100 dark:bg-orange-500/20 rounded-full">
                <svg class="w-6 h-6 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Usage Trend</h3>
            <canvas id="usageChart" class="h-80"></canvas>
        </div>
        <div class="lg:col-span-2 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Questions by Subject</h3>
            <canvas id="subjectChart" class="h-80"></canvas>
        </div>
    </div>

    {{-- Modern Recent Questions Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-x-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Questions</h3>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="p-4 font-semibold">Question</th>
                <th class="p-4 font-semibold">Subject</th>
                <th class="p-4 font-semibold">Created</th>
                <th class="p-4 font-semibold">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <td class="p-4">What is the SI unit of force?</td>
                <td class="p-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Physics</span></td>
                <td class="p-4 text-gray-500 dark:text-gray-400">2 hrs ago</td>
                <td class="p-4 space-x-2">
                    <button class="text-indigo-500 hover:text-indigo-700 font-medium">Edit</button>
                    <button class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                </td>
            </tr>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <td class="p-4">Photosynthesis occurs in?</td>
                <td class="p-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Biology</span></td>
                <td class="p-4 text-gray-500 dark:text-gray-400">5 hrs ago</td>
                <td class="p-4 space-x-2">
                    <button class="text-indigo-500 hover:text-indigo-700 font-medium">Edit</button>
                    <button class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                </td>
            </tr>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <td class="p-4">What is H<sub>2</sub>O commonly called?</td>
                <td class="p-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Chemistry</span></td>
                <td class="p-4 text-gray-500 dark:text-gray-400">1 day ago</td>
                <td class="p-4 space-x-2">
                    <button class="text-indigo-500 hover:text-indigo-700 font-medium">Edit</button>
                    <button class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</div>

{{-- ✅ Scripts --}}
<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebarTextEls = document.querySelectorAll('.sidebar-text');
    const mainContent = document.getElementById('mainContent');

    function setSidebarState(collapsed) {
        const navLinks = document.querySelectorAll('.nav-link, .submenu-toggle');
        const submenus = document.querySelectorAll('.submenu');

        if (collapsed) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            mainContent.classList.remove('md:ml-64');
            mainContent.classList.add('md:ml-20');

            sidebarTextEls.forEach(el => el.classList.add('hidden'));
            navLinks.forEach(link => link.classList.add('justify-center'));

            submenus.forEach(menu => {
                const items = menu.querySelector('.submenu-items');
                const arrow = menu.querySelector('.arrow');
                if (arrow) arrow.classList.add('hidden');
                if (items) {
                    items.style.maxHeight = null; // Close any open submenus
                    items.classList.remove('hidden'); // Ensure it's not display:none for popout to work
                    items.classList.add(
                        'absolute', 'left-full', 'top-0', 'ml-2', 'z-20',
                        'bg-white', 'dark:bg-gray-700', 'rounded-md', 'shadow-lg', 'p-2', 'min-w-[150px]',
                        'invisible', 'opacity-0', 'scale-95', 'transition-all', 'duration-200'
                    );
                }
            });

            localStorage.setItem('sidebar', 'collapsed');
        } else {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            mainContent.classList.remove('md:ml-20');
            mainContent.classList.add('md:ml-64');

            sidebarTextEls.forEach(el => el.classList.remove('hidden'));
            navLinks.forEach(link => link.classList.remove('justify-center'));

            submenus.forEach(menu => {
                const items = menu.querySelector('.submenu-items');
                const arrow = menu.querySelector('.arrow');
                if (arrow) arrow.classList.remove('hidden');
                if (items) {
                    if (!items.querySelector('.active-link')) {
                        items.style.maxHeight = null; // Keep it closed
                    }
                    items.classList.remove(
                        'absolute', 'left-full', 'top-0', 'ml-2', 'z-20',
                        'bg-white', 'dark:bg-gray-700', 'rounded-md', 'shadow-lg', 'p-2', 'min-w-[150px]',
                        'invisible', 'opacity-0', 'scale-95', 'transition-all', 'duration-200'
                    );
                }
            });

            localStorage.setItem('sidebar', 'expanded');
        }
        styleActiveParentMenu();
    }

    function styleActiveParentMenu() {
        document.querySelectorAll('.submenu-toggle.active-parent').forEach(el => {
            el.classList.remove('active-parent', 'bg-indigo-50', 'dark:bg-gray-700', 'text-indigo-600', 'dark:text-indigo-400', 'font-semibold');
        });

        if (sidebar.classList.contains('w-20')) {
            const activeSubmenuLink = document.querySelector('.submenu-items .active-link');
            if (activeSubmenuLink) {
                const parentToggle = activeSubmenuLink.closest('.submenu').querySelector('.submenu-toggle');
                if (parentToggle) {
                    parentToggle.classList.add('active-parent', 'bg-indigo-50', 'dark:bg-gray-700', 'text-indigo-600', 'dark:text-indigo-400', 'font-semibold');
                }
            }
        }
    }

    if (localStorage.getItem('sidebar') === 'collapsed') {
        setSidebarState(true);
    } else {
        setSidebarState(false);
    }

    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('w-20');
            setSidebarState(!isCollapsed);
        });
    }

    // --- Other scripts (Mobile Toggle, Dark Mode, User Menu)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const overlay = document.createElement('div');
    overlay.className = "fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden";
    document.body.appendChild(overlay);
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
    }
    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    const darkToggle = document.getElementById('darkToggle');
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
        if(darkToggle) darkToggle.checked = true;
    }
    if (darkToggle) {
        darkToggle.addEventListener('change', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });
    }

    document.querySelectorAll('.submenu-toggle').forEach(toggleBtn => {
        const submenuItems = toggleBtn.nextElementSibling;
        const arrow = toggleBtn.querySelector('svg.arrow');
        toggleBtn.addEventListener('click', () => {
            if (sidebar.classList.contains('w-20')) return;

            if (submenuItems.style.maxHeight) {
                submenuItems.style.maxHeight = null;
                arrow.classList.remove('rotate-180');
            } else {
                submenuItems.style.maxHeight = submenuItems.scrollHeight + 'px';
                arrow.classList.add('rotate-180');
            }
        });
    });

    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');
    if (userMenuButton) {
        userMenuButton.addEventListener('click', (event) => {
            event.stopPropagation();
            userMenu.classList.toggle('hidden');
        });
    }
    window.addEventListener('click', (event) => {
        if (userMenu && !userMenu.classList.contains('hidden') && !userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
            userMenu.classList.add('hidden');
        }
    });

    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active-link', 'bg-indigo-50', 'dark:bg-gray-700', 'text-indigo-600', 'dark:text-indigo-400', 'font-semibold');
            const parentSubmenu = link.closest('.submenu');
            if(parentSubmenu && !sidebar.classList.contains('w-20')){
                const submenuItems = parentSubmenu.querySelector('.submenu-items');
                const arrow = parentSubmenu.querySelector('.arrow');
                submenuItems.style.maxHeight = submenuItems.scrollHeight + 'px';
                arrow.classList.add('rotate-180');
            }
        } else {
            link.classList.add('hover:bg-gray-100', 'dark:hover:bg-gray-700', 'text-gray-600', 'dark:text-gray-300');
        }
    });

    // Initial check on a page load
    styleActiveParentMenu();
    // Chart initializations would go here...
    new Chart(document.getElementById('subjectChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Math', 'Physics', 'Chemistry', 'Biology'],
            datasets: [{
                label: 'Questions',
                data: [1200, 800, 950, 600],
                backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444'],
                borderRadius: 8
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('usageChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            datasets: [{
                label: 'Daily Attempts',
                data: [120, 150, 200, 180, 250, 300, 280],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
</body>
</html>
