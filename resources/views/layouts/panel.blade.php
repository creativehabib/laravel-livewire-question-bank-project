<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MCQ Bank' }}</title>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased">
    <livewire:admin.partials.sidebar />
    {{-- Topbar/Header --}}
    <livewire:admin.partials.header />
    <div id="mainContent" class="flex-1 md:ml-64 print:p-0 print:space-y-0 p-4 md:p-8 space-y-6 transition-all duration-200">
        {{ $slot }}
    </div>
</div>

{{-- Scripts --}}
@auth
    <livewire:auth.role-prompt />
    <livewire:chat-popup />
@endauth
<script>
    // --- Sidebar Collapse / Expand ---
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
                    items.style.maxHeight = null;
                    items.classList.remove('hidden');
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
                        items.style.maxHeight = null;
                    }
                    items.classList.remove(
                        'absolute','left-full','top-0','ml-2','z-20',
                        'bg-white','dark:bg-gray-700','rounded-md','shadow-lg','p-2','min-w-[150px]',
                        'invisible','opacity-0','scale-95','transition-all','duration-200'
                    );
                }
            });

            localStorage.setItem('sidebar', 'expanded');
        }
        styleActiveParentMenu();
    }

    function styleActiveParentMenu() {
        document.querySelectorAll('.submenu-toggle.active-parent').forEach(el => {
            el.classList.remove('active-parent','bg-indigo-50','dark:bg-gray-700','text-indigo-600','dark:text-indigo-400','font-semibold');
        });

        if (sidebar.classList.contains('w-20')) {
            const activeSubmenuLink = document.querySelector('.submenu-items .active-link');
            if (activeSubmenuLink) {
                const parentToggle = activeSubmenuLink.closest('.submenu').querySelector('.submenu-toggle');
                if (parentToggle) {
                    parentToggle.classList.add('active-parent','bg-indigo-50','dark:bg-gray-700','text-indigo-600','dark:text-indigo-400','font-semibold');
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

    // --- Mobile Overlay ---
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

    // --- Submenu toggle ---
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

    // --- User Menu ---
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

    // --- Active Link Highlight ---
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active-link','bg-indigo-50','dark:bg-gray-700','text-indigo-600','dark:text-indigo-400','font-semibold');
            const parentSubmenu = link.closest('.submenu');
            if (parentSubmenu && !sidebar.classList.contains('w-20')) {
                const submenuItems = parentSubmenu.querySelector('.submenu-items');
                const arrow = parentSubmenu.querySelector('.arrow');
                submenuItems.style.maxHeight = submenuItems.scrollHeight + 'px';
                arrow.classList.add('rotate-180');
            }
        } else {
            link.classList.add('hover:bg-gray-100','dark:hover:bg-gray-700','text-gray-600','dark:text-gray-300');
        }
    });

    styleActiveParentMenu();

    // --- Example Charts ---
    if (window.Chart) {
        const subjectEl = document.getElementById('subjectChart');
        if (subjectEl) {
            new Chart(subjectEl.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Math','Physics','Chemistry','Biology'],
                    datasets: [{
                        label: 'Questions',
                        data: [1200, 800, 950, 600],
                        backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444'],
                        borderRadius: 8
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }

        const usageEl = document.getElementById('usageChart');
        if (usageEl) {
            new Chart(usageEl.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                    datasets: [{
                        label: 'Daily Attempts',
                        data: [120,150,200,180,250,300,280],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99,102,241,0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }
    }
</script>

{{-- ✅ Livewire Scripts --}}
@livewireScripts

{{-- MathJax Configuration --}}
<script>
    window.MathJax = {
        tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
        svg: { fontCache: 'global' }
    };
</script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

{{-- ✅ Extra scripts --}}
@stack('scripts')
</body>
</html>
