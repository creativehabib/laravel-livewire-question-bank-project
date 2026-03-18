<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MCQ Bank' }}</title>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css','resources/js/app.js'])
    @include('components.settings.font-loader')
    @livewireStyles
</head>
<body class="app-shell">
<div class="min-h-screen app-shell">
    <livewire:admin.partials.sidebar />

    <div id="mainContent" data-sidebar-content class="space-y-6 p-4 print:space-y-0 print:p-0 md:ml-64 md:p-8">
        <livewire:admin.partials.header />
        {{ $slot }}
    </div>

    <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black bg-opacity-50 print:hidden md:hidden"></div>
</div>

@auth
    <livewire:auth.role-prompt />
    <livewire:chat-popup />
@endauth

@livewireScripts

<script>
    window.MathJax = {
        tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
        svg: { fontCache: 'global' }
    };
</script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

@stack('scripts')
</body>
</html>
