<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MCQ Bank' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.settings.font-loader')
</head>
<body class="app-shell font-sans antialiased">
    <header class="app-panel sticky top-0 z-30 border-b border-white/50 shadow-none">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <a href="/" class="app-heading text-xl font-bold">MCQ Bank</a>
            <nav class="flex items-center gap-4 text-sm font-medium">
                @auth
                    <a href="{{ route('dashboard') }}" class="app-link text-sm font-medium">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="app-link text-sm font-medium">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="app-link text-sm font-medium">Register</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer class="app-panel mx-4 mt-12 rounded-[1.75rem] border px-6 py-6 text-center text-sm sm:mx-6 lg:mx-8">
        &copy; {{ date('Y') }} MCQ Bank. All rights reserved.
    </footer>
    {{-- MathJax Configuration --}}
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</body>
</html>
