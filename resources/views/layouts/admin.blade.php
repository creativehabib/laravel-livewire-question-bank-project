<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MCQ Bank - ফাইনাল ম্যানেজার' }}</title>

    <script src="/ckeditor/ckeditor.js" type="text/javascript"></script>

    <script>
        // ডার্ক মোডের জন্য এই অংশটুকু এখানে রাখা জরুরি, যাতে পেজ লোডের সময় কোনো ফ্লিকার না হয়।
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css','resources/js/app.js'])
    @include('components.settings.font-loader')

    @livewireStyles
    @stack('styles')
</head>
<body class="bg-[#f8f9fa] font-sans antialiased text-gray-800 dark:bg-gray-900 dark:text-gray-200">

<div class="flex min-h-screen w-full">
    {{-- Sidebar --}}
    <livewire:admin.partials.sidebar />

    {{-- Main Content Wrapper --}}
    <div data-sidebar-content class="flex flex-1 flex-col min-w-0 w-full transition-all duration-300 md:ml-64">

        {{-- Header --}}
        <livewire:admin.partials.header />

        {{-- Main Page Content --}}
        <main id="mainContent" class="w-full max-w-7xl mx-auto flex-1 space-y-6 p-4 print:p-0 md:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    {{-- Mobile Sidebar Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-gray-900/50 backdrop-blur-sm print:hidden md:hidden"></div>
</div>

@auth
    <livewire:auth.role-prompt />
    <livewire:chat-popup />
@endauth

@livewireScripts

{{-- MathJax Configuration --}}
<script>
    window.MathJax = {
        tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
        svg: { fontCache: 'global' }
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js" id="MathJax-script" async></script>

{{-- Livewire + MathJax Rendering Logic --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Livewire v3 এর জন্য: পেজ নেভিগেশন বা কম্পোনেন্ট আপডেটের পর MathJax রেন্ডার করা
        document.addEventListener('livewire:navigated', () => {
            if (window.MathJax) {
                MathJax.typesetPromise();
            }
        });

        // Livewire v2 ব্যবহার করলে নিচের কোডটি আনকমেন্ট করুন এবং উপরেরটি মুছে দিন
        /*
        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', () => {
                if (window.MathJax) {
                    MathJax.typesetPromise();
                }
            });
        });
        */
    });
</script>

{{-- নির্দিষ্ট পেজের জন্য কোনো স্ক্রিপ্ট থাকলে এখানে পুশ হবে --}}
@stack('scripts')
</body>
</html>
