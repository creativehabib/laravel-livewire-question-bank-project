<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
    {{-- Hero Section --}}
    <section class="bg-blue-600 text-white text-center py-20">

        <div class="container mx-auto">
            <h1 class="text-4xl lg:text-5xl font-extrabold mb-4">প্রস্তুতি ও ক্যারিয়ার, এখন একসাথেই</h1>
            <p class="text-lg lg:text-xl mb-8 max-w-2xl mx-auto">হাজারো প্রশ্নে অনুশীলন করুন এবং আপনার স্বপ্নের চাকরির সন্ধান করুন সেরা একটি প্ল্যাটফর্মে।</p>
            <div class="space-x-4">
                <a href="{{ route('quizzes.index') }}" class="bg-white text-blue-600 font-bold py-3 px-8 rounded-full hover:bg-gray-200 transition text-lg">অনুশীলন শুরু করুন</a>
                <a href="{{ route('jobs.index') }}" class="border-2 border-white font-bold py-3 px-8 rounded-full hover:bg-white hover:text-blue-600 transition text-lg">চাকরি খুঁজুন</a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-16">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-10">আমাদের প্রধান সেবা সমূহ</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition">
                    <h3 class="text-xl font-bold mb-3">MCQ অনুশীলন</h3>
                    <p class="text-gray-600">বিষয়ভিত্তিক এবং মডেল টেস্টের মাধ্যমে চাকরির পরীক্ষার জন্য নিজেকে প্রস্তুত করুন।</p>
                </div>
                {{-- Feature 2 --}}
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition">
                    <h3 class="text-xl font-bold mb-3">লিখিত প্রশ্ন ভান্ডার</h3>
                    <p class="text-gray-600">বিগত বছরের পরীক্ষার বর্ণনামূলক প্রশ্ন ও সমাধান দেখুন এবং প্রস্তুতিকে আরও শক্তিশালী করুন।</p>
                </div>
                {{-- Feature 3 --}}
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition">
                    <h3 class="text-xl font-bold mb-3">সেরা চাকরির খবর</h3>
                    <p class="text-gray-600">বাংলাদেশের সেরা কোম্পানিগুলোর সর্বশেষ চাকরির বিজ্ঞপ্তি第一时间 আপনার হাতের মুঠোয়।</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Recent Jobs Section --}}
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-10">সাম্প্রতিক চাকরি</h2>
            <div class="space-y-4 max-w-4xl mx-auto">
                {{-- Job Item (Loop through recent jobs here) --}}
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-lg text-gray-800">Software Engineer</h4>
                        <p class="text-gray-600">ABC Company Ltd. - ঢাকা</p>
                    </div>
                    <a href="#" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-md text-sm hover:bg-blue-200">বিস্তারিত</a>
                </div>
                {{-- Add more job items --}}
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('jobs.index') }}" class="text-blue-600 font-semibold">সব চাকরি দেখুন &rarr;</a>
            </div>
        </div>
    </section>
    </body>
</html>
