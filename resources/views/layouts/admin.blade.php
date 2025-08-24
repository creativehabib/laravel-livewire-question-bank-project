<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MCQ Bank' }}</title>
    <script>
        // ডার্ক মোডের জন্য এই অংশটুকু এখানে রাখা জরুরি, যাতে পেজ লোডের সময় কোনো ফ্লিকার না হয়।
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    {{-- Vite Assets (app.css এবং app.js এখান থেকে লোড হবে) --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased">
        <livewire:admin.partials.sidebar />
        {{-- Main Content --}}
        <div id="mainContent" class="flex-1 md:ml-64 p-4 md:p-8 space-y-6 transition-all duration-200">
            <livewire:admin.partials.header />
            {{ $slot }}
        </div>

        {{-- Mobile Sidebar Overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden"></div>
    </div>
    @auth
        <livewire:chat-popup />
    @endauth
    @livewireScripts

    <script>
        const sidebar = document.getElementById('sidebar');
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
    </script>
    {{-- MathJax Configuration (যদি MathJax ব্যবহার করেন) --}}
    @if(isset($usesMath) && $usesMath)
        <script>
            window.MathJax = {
                tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
                svg: { fontCache: 'global' }
            };
        </script>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>
    @endif

    {{-- নির্দিষ্ট পেজের জন্য কোনো স্ক্রিপ্ট থাকলে এখানে পুশ হবে --}}
    @stack('scripts')
</body>
</html>
