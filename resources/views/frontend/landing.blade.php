@extends('layouts.frontend')

@section('content')
<section class="py-24 text-center">
    <h1 class="text-5xl font-bold mb-4">Welcome to MCQ Bank</h1>
    <p class="text-lg text-gray-600 mb-8">Practice and create multiple choice questions effortlessly.</p>
    <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md">Get Started</a>
</section>
@endsection
