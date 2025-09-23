<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MCQ Bank' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.settings.font-loader')
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <a href="/" class="text-xl font-bold">MCQ Bank</a>
            <nav class="space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">Register</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer class="bg-white border-t mt-12 py-6 text-center text-sm text-gray-500">
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
