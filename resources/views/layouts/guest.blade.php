<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @include('components.settings.font-loader')
    </head>
    <body class="app-shell font-sans antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 pt-6 sm:px-6 sm:pt-0">
            <div>
                <a href="/" wire:navigate>
                    <x-application-logo class="h-20 w-20 fill-current text-indigo-500 dark:text-indigo-300" />
                </a>
            </div>

            <div class="app-panel w-full overflow-hidden rounded-[1.75rem] px-6 py-6 shadow-none sm:mt-6 sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
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
