@extends('layouts.frontend')

@section('content')
<section class="py-24 text-center">
    <h1 class="text-5xl font-bold mb-4">Welcome to MCQ Bank</h1>
    <p class="text-lg text-gray-600 mb-8">Practice and create multiple choice questions effortlessly.</p>
    @auth
        <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md">Go to Dashboard</a>
    @else
        <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md">Get Started</a>
    @endauth
</section>

<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div>
            <h3 class="text-xl font-semibold mb-2">Create</h3>
            <p class="text-gray-600">Build your own multiple choice questions with an intuitive interface.</p>
        </div>
        <div>
            <h3 class="text-xl font-semibold mb-2">Practice</h3>
            <p class="text-gray-600">Sharpen your skills by practicing questions across various subjects.</p>
        </div>
        <div>
            <h3 class="text-xl font-semibold mb-2">Share</h3>
            <p class="text-gray-600">Collaborate with others by sharing your question sets.</p>
        </div>
    </div>
</section>

<section class="py-24 text-center bg-indigo-50">
    <h2 class="text-3xl font-bold mb-4">Ready to dive in?</h2>
    @auth
        <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md">Go to Dashboard</a>
    @else
        <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md">Create an Account</a>
    @endauth
</section>
@endsection
