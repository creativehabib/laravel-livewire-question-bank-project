<div class="space-y-8">
    @php
        $user = auth()->user();
        $averageQuestionsPerSet = $questionSetCount > 0
            ? number_format($questionCount / max($questionSetCount, 1), 1)
            : '0.0';
        $recentQuestionSet = $questionSets->first();
        $activeSubjects = $subjects->where('questions_count', '>', 0)->count();
        $topSubject = $subjects->sortByDesc('questions_count')->first();
        $totalQuestionsForPercentage = max($questionCount, 1);
        $topSubjectPercentage = $topSubject
            ? round(($topSubject->questions_count / $totalQuestionsForPercentage) * 100)
            : 0;
        $topSubjects = $subjects->sortByDesc('questions_count')->take(3);
        $activityMonths = $activityTimeline->count();
        $totalQuestionsInTimeline = $activityTimeline->sum('questions');
        $totalQuestionSetsInTimeline = $activityTimeline->sum('question_sets');
    @endphp

    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-emerald-500 p-8 text-white shadow-xl dark:from-slate-950 dark:via-indigo-900 dark:to-emerald-800">
        <div class="pointer-events-none absolute inset-0 opacity-25">
            <div class="absolute -right-12 -top-20 h-64 w-64 rounded-full bg-white/20 blur-3xl dark:bg-white/10"></div>
            <div class="absolute bottom-0 left-12 h-56 w-56 rounded-full bg-white/10 blur-2xl dark:bg-emerald-500/20"></div>
            <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/30 via-transparent to-transparent dark:from-black/50"></div>
        </div>

        <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-6">
                <div class="flex flex-wrap items-center gap-3 text-xs uppercase tracking-widest text-white/70">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.75 4A2.75 2.75 0 015.5 1.25h9A2.75 2.75 0 0117.25 4v12A2.75 2.75 0 0114.5 18.75h-9A2.75 2.75 0 012.75 16V4zM5.5 3.25a.75.75 0 00-.75.75v12c0 .414.336.75.75.75h9a.75.75 0 00.75-.75V4a.75.75 0 00-.75-.75h-9z" />
                        </svg>
                        ড্যাশবোর্ড
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v7.5m3.75-3.75h-7.5" />
                        </svg>
                        শিক্ষকের কন্ট্রোল সেন্টার
                    </span>
                </div>

                <div class="space-y-3">
                    <h1 class="text-3xl font-semibold md:text-4xl">স্বাগতম, {{ $user->name }}!</h1>
                    <p class="max-w-2xl text-sm md:text-base text-white/80">
                        আপনার তৈরি করা প্রশ্ন ও প্রশ্ন সেটগুলোর কার্যকারিতা বিশ্লেষণ করুন, দ্রুত নতুন কনটেন্ট তৈরি করুন এবং শিক্ষার্থীদের জন্য সর্বোত্তম প্রস্তুতি নিশ্চিত করুন।
                    </p>
                    <div class="flex flex-wrap gap-3 text-sm text-white/80">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5m-7.5 3.75h7.5M5.25 21h13.5A2.25 2.25 0 0021 18.75V5.25A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25v13.5A2.25 2.25 0 005.25 21z" />
                            </svg>
                            বিষয় সক্রিয়: {{ $activeSubjects }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            সর্বশেষ আপডেট: {{ $recentQuestionSet?->updated_at?->diffForHumans() ?? 'তথ্য নেই' }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a6 6 0 100 12 6 6 0 000-12z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V3m0 18v-3m6-6h3M3 12h3" />
                            </svg>
                            সর্বাধিক সক্রিয় বিষয়: {{ $topSubject?->name ?? 'ডেটা নেই' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button
                        type="button"
                        data-trigger-theme-toggle
                        class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/40"
                    >
                        <span class="flex items-center gap-2 dark:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25M18.364 5.636l-1.59 1.59M21 12h-2.25M18.364 18.364l-1.59-1.59M12 18.75V21m-4.774-4.226l-1.59 1.59M5.25 12H3m3.226-4.774l-1.59-1.59M12 8.25a3.75 3.75 0 110 7.5 3.75 3.75 0 010-7.5z" />
                            </svg>
                            ডার্ক মোড চালু করুন
                        </span>
                        <span class="hidden items-center gap-2 dark:flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.75A9.75 9.75 0 1111.25 3a7.501 7.501 0 009.75 9.75z" />
                            </svg>
                            লাইট মোড চালু করুন
                        </span>
                    </button>

                    <a
                        wire:navigate
                        href="{{ route('practice') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/25 focus:outline-none focus:ring-2 focus:ring-white/40"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 016 4.5h3.75A2.25 2.25 0 0112 6.75v10.5A2.25 2.25 0 019.75 19.5H6a2.25 2.25 0 01-2.25-2.25V6.75z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75A2.25 2.25 0 0114.25 4.5H18a2.25 2.25 0 012.25 2.25v6.75a2.25 2.25 0 01-2.25 2.25h-3.75A2.25 2.25 0 0112 13.5V6.75z" />
                        </svg>
                        প্র্যাকটিস সেশন চালু করুন
                    </a>
                </div>
            </div>

            <div class="flex w-full flex-col gap-4 rounded-2xl bg-black/20 p-6 backdrop-blur lg:max-w-sm dark:bg-white/10">
                <div>
                    <p class="text-xs uppercase tracking-widest text-white/70">সর্বশেষ প্রশ্ন সেট</p>
                    <p class="mt-1 text-lg font-semibold">
                        {{ $recentQuestionSet?->name ?? 'এখনো তৈরি করা হয়নি' }}
                    </p>
                    <p class="mt-2 text-xs text-white/70">
                        @if ($recentQuestionSet)
                            আপডেট হয়েছে {{ $recentQuestionSet->updated_at?->diffForHumans() }} আগে
                        @else
                            এখনো কোনো প্রশ্ন সেট তৈরি করা হয়নি।
                        @endif
                    </p>
                </div>

                <div class="grid gap-3 rounded-xl bg-white/10 p-4 text-xs text-white/80 shadow-inner">
                    <div class="flex items-center justify-between">
                        <span>মোট প্রশ্ন</span>
                        <span class="font-semibold text-white">{{ $questionCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>মোট প্রশ্ন সেট</span>
                        <span class="font-semibold text-white">{{ $questionSetCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>সক্রিয় বিষয়</span>
                        <span class="font-semibold text-white">{{ $activeSubjects }}</span>
                    </div>
                </div>

                <a
                    wire:navigate
                    href="{{ route('questions.set.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-emerald-600 shadow hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-white/40"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    নতুন প্রশ্ন সেট তৈরি করুন
                </a>
            </div>
        </div>
    </section>

    @if (session()->has('success'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
            <div class="mt-1 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="relative overflow-hidden rounded-2xl border border-indigo-100 bg-white p-6 shadow-sm dark:border-indigo-500/30 dark:bg-gray-900">
            <div class="absolute right-0 top-0 h-28 w-28 bg-indigo-500/10 blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">মোট প্রশ্ন</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $questionCount }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/15 text-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h18M3 9h18M3 12.75h18M3 16.5h18" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">সবগুলো প্রশ্নের অগ্রগতি ও মান নিয়মিত পর্যবেক্ষণ করুন।</p>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-purple-100 bg-white p-6 shadow-sm dark:border-purple-500/30 dark:bg-gray-900">
            <div class="absolute right-0 top-0 h-28 w-28 bg-purple-500/10 blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">মোট প্রশ্ন সেট</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $questionSetCount }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500/15 text-purple-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5M12 7.5v12m-3 0h6" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">বিভিন্ন স্তরের শিক্ষার্থীদের জন্য আলাদা আলাদা সেট তৈরি করে রাখুন।</p>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm dark:border-emerald-500/30 dark:bg-gray-900">
            <div class="absolute right-0 top-0 h-28 w-28 bg-emerald-500/10 blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">গড় প্রশ্ন / সেট</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $averageQuestionsPerSet }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l5.25-5.25 3 3L19.5 7.5" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">সুনির্দিষ্ট লক্ষ্য অনুযায়ী প্রতিটি সেট সমৃদ্ধ রাখুন।</p>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-sky-100 bg-white p-6 shadow-sm dark:border-sky-500/30 dark:bg-gray-900">
            <div class="absolute right-0 top-0 h-28 w-28 bg-sky-500/10 blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">সর্বাধিক সমৃদ্ধ বিষয়</p>
                    <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $topSubject?->name ?? 'ডেটা নেই' }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/15 text-sky-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 space-y-3">
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>মোট প্রশ্ন: {{ $topSubject?->questions_count ?? 0 }}</span>
                    <span>{{ $topSubjectPercentage }}%</span>
                </div>
                <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                    <div class="h-2 rounded-full bg-sky-500 dark:bg-sky-400" style="width: {{ $topSubjectPercentage }}%;"></div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">ড্যাশবোর্ডের সর্বাধিক প্রশ্ন এই বিষয়ের অধীনে রয়েছে। ভারসাম্য বজায় রাখতে প্রয়োজন অনুসারে অন্যান্য বিষয়েও প্রশ্ন যুক্ত করুন।</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div
                    class="flex flex-col gap-2 border-b border-gray-100 px-6 py-5 dark:border-gray-800 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">মাসিক কার্যক্রম বিশ্লেষণ</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            শেষ {{ $activityMonths }} মাসে প্রশ্ন ও প্রশ্ন সেট তৈরির প্রবণতা পর্যবেক্ষণ করুন।
                        </p>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 md:text-right">
                        <p>
                            মোট প্রশ্ন:
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $totalQuestionsInTimeline }}</span>
                        </p>
                        <p>
                            মোট প্রশ্ন সেট:
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $totalQuestionSetsInTimeline }}</span>
                        </p>
                    </div>
                </div>
                <div class="px-4 pb-6 pt-2 md:px-6">
                    <div id="activityChart" class="h-72"></div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex flex-col gap-2 border-b border-gray-100 px-6 py-5 dark:border-gray-800 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">বিষয় ভিত্তিক প্রশ্ন বিশ্লেষণ</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">প্রতিটি বিষয়ের প্রশ্ন সংখ্যা তুলনা করে সুষম প্রস্তুতি নিশ্চিত করুন।</p>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-indigo-100 px-3 py-1 text-xs font-medium text-indigo-600 dark:border-indigo-500/40 dark:text-indigo-300">
                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                        লাইভ ডাটা
                    </span>
                </div>
                <div class="px-4 pb-6 pt-2 md:px-6">
                    <div id="subjectChart" class="h-72"></div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-5 dark:border-gray-800 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">আপনার প্রশ্ন সেট তালিকা</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">সর্বশেষ তৈরি করা প্রশ্ন সেটগুলো পর্যবেক্ষণ ও সম্পাদনা করুন।</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a
                            wire:navigate
                            href="{{ route('questions.view') }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:text-gray-900 dark:border-gray-700 dark:text-gray-300 dark:hover:border-gray-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12c0 1.06.21 2.07.6 3h18.3a8.25 8.25 0 10-18.9-3z" />
                            </svg>
                            সব প্রশ্ন দেখুন
                        </a>
                        <a
                            wire:navigate
                            href="{{ route('questions.set.create') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            নতুন সেট
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm dark:divide-gray-800">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">প্রশ্ন সেট</th>
                                <th scope="col" class="px-6 py-3">মোট প্রশ্ন</th>
                                <th scope="col" class="px-6 py-3">তৈরীর তারিখ</th>
                                <th scope="col" class="px-6 py-3">সর্বশেষ আপডেট</th>
                                <th scope="col" class="px-6 py-3 text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                            @forelse ($questionSets as $questionSet)
                                <tr wire:key="question-set-{{ $questionSet->id }}" class="transition hover:bg-gray-50 dark:hover:bg-gray-800/60">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $questionSet->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">আইডি: {{ $questionSet->id }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-300">{{ $questionSet->questions_count }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-300">{{ $questionSet->created_at?->format('d M, Y h:i A') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-300">{{ $questionSet->updated_at?->diffForHumans() }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a
                                                wire:navigate
                                                href="{{ route('questions.view', ['qset' => $questionSet->id]) }}"
                                                class="inline-flex items-center gap-1 rounded-lg border border-indigo-200 px-3 py-1.5 text-xs font-medium text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-50 dark:border-indigo-500/40 dark:text-indigo-300 dark:hover:border-indigo-400/60 dark:hover:bg-indigo-500/10"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v2.25M3 8.25v9A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-9M3 8.25l7.6 5.7a2.25 2.25 0 002.8 0l7.6-5.7" />
                                                </svg>
                                                এডিট
                                            </a>
                                            <button
                                                wire:click="deleteQuestionSet('{{ $questionSet->id }}')"
                                                wire:confirm="আপনি কি নিশ্চিত যে এই প্রশ্ন সেটটি মুছে ফেলতে চান?"
                                                class="inline-flex items-center gap-1 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:border-red-300 hover:bg-red-50 dark:border-red-500/40 dark:text-red-300 dark:hover:border-red-400/60 dark:hover:bg-red-500/10"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.108 1.022.169m-1.022-.17L18.16 19.673A2.25 2.25 0 0115.916 21H8.084a2.25 2.25 0 01-2.244-2.327L6.772 5.79m12.456 0a48.108 48.108 0 00-3.478-.397m-12 .566c.34-.061.68-.117 1.022-.169m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                                ডিলিট
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                        এখনো কোনো প্রশ্ন সেট তৈরি করা হয়নি। নতুন প্রশ্ন সেট তৈরি করে ড্যাশবোর্ড পূর্ণ করুন।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">দ্রুত কার্যাবলী</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">প্রয়োজনীয় কাজগুলো এক ক্লিকে সম্পন্ন করুন।</p>
                </div>
                <div class="space-y-3 px-6 py-5">
                    <a
                        wire:navigate
                        href="{{ route('teacher.questions.create') }}"
                        class="group flex items-center justify-between rounded-xl border border-indigo-100 px-4 py-3 text-sm font-medium text-indigo-600 transition hover:border-indigo-200 hover:bg-indigo-50 dark:border-indigo-500/30 dark:text-indigo-300 dark:hover:border-indigo-400/40 dark:hover:bg-indigo-500/10"
                    >
                        নতুন প্রশ্ন তৈরি করুন
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 transition group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <a
                        wire:navigate
                        href="{{ route('teacher.questions.index') }}"
                        class="group flex items-center justify-between rounded-xl border border-purple-100 px-4 py-3 text-sm font-medium text-purple-600 transition hover:border-purple-200 hover:bg-purple-50 dark:border-purple-500/30 dark:text-purple-300 dark:hover:border-purple-400/40 dark:hover:bg-purple-500/10"
                    >
                        প্রশ্ন ব্যাংক ম্যানেজ করুন
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 transition group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M3 9h18M9 13.5h12M9 18h12" />
                        </svg>
                    </a>
                    <a
                        wire:navigate
                        href="{{ route('teacher.questions.generate') }}"
                        class="group flex items-center justify-between rounded-xl border border-emerald-100 px-4 py-3 text-sm font-medium text-emerald-600 transition hover:border-emerald-200 hover:bg-emerald-50 dark:border-emerald-500/30 dark:text-emerald-300 dark:hover:border-emerald-400/40 dark:hover:bg-emerald-500/10"
                    >
                        স্মার্ট প্রশ্ন জেনারেটর
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 transition group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v9m0 0l3.75-3.75M12 12L8.25 8.25m3.75 3.75v9" />
                        </svg>
                    </a>
                    <a
                        wire:navigate
                        href="{{ route('questions.paper') }}"
                        class="group flex items-center justify-between rounded-xl border border-orange-100 px-4 py-3 text-sm font-medium text-orange-600 transition hover:border-orange-200 hover:bg-orange-50 dark:border-orange-500/30 dark:text-orange-300 dark:hover:border-orange-400/40 dark:hover:bg-orange-500/10"
                    >
                        পরীক্ষার প্রশ্নপত্র প্রস্তুত করুন
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 transition group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5h10.5M8.25 9H12m-3.75 4.5H12m-3.75 4.5H12M6 18.75h12A2.25 2.25 0 0020.25 16.5v-9a2.25 2.25 0 00-2.25-2.25H9.75a2.25 2.25 0 00-2.25 2.25V18a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 003 18V8.25" />
                        </svg>
                    </a>
                    <a
                        wire:navigate
                        href="{{ route('practice') }}"
                        class="group flex items-center justify-between rounded-xl border border-sky-100 px-4 py-3 text-sm font-medium text-sky-600 transition hover:border-sky-200 hover:bg-sky-50 dark:border-sky-500/30 dark:text-sky-300 dark:hover:border-sky-400/40 dark:hover:bg-sky-500/10"
                    >
                        লাইভ প্র্যাকটিস বোর্ড
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 transition group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                    <a
                        href="{{ route('profile') }}"
                        class="group flex items-center justify-between rounded-xl border border-rose-100 px-4 py-3 text-sm font-medium text-rose-600 transition hover:border-rose-200 hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:border-rose-400/40 dark:hover:bg-rose-500/10"
                    >
                        প্রোফাইল সেটিংস আপডেট করুন
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 transition group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6.768-6.768a2.5 2.5 0 013.536 3.536L12.536 14.5 9 15l.5-3.536z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 19.5h14" />
                        </svg>
                    </a>
                </div>
            </div>

            <div
                class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
                x-data="{
                    focusMode: JSON.parse(localStorage.getItem('teacher-focus-mode') ?? 'false'),
                    reminders: JSON.parse(localStorage.getItem('teacher-reminders') ?? 'true'),
                    shareSummary: JSON.parse(localStorage.getItem('teacher-share-summary') ?? 'false'),
                }"
                x-init="
                    $watch('focusMode', value => localStorage.setItem('teacher-focus-mode', JSON.stringify(value)));
                    $watch('reminders', value => localStorage.setItem('teacher-reminders', JSON.stringify(value)));
                    $watch('shareSummary', value => localStorage.setItem('teacher-share-summary', JSON.stringify(value)));
                "
            >
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">টিচার কন্ট্রোল সেন্টার</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">ড্যাশবোর্ডের অগ্রাধিকার ও অনুস্মারকগুলো নিজের পছন্দমতো সাজিয়ে নিন।</p>
                </div>
                <div class="space-y-5 px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">ফোকাস মোড</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">অপ্রয়োজনীয় নোটিফিকেশন এড়িয়ে মনোযোগী প্রস্তুতি নিন।</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="relative inline-flex h-8 w-14 items-center rounded-full transition"
                                :class="focusMode ? 'bg-emerald-500/90 shadow shadow-emerald-500/20' : 'bg-gray-200 dark:bg-gray-700'"
                                @click="focusMode = !focusMode"
                                :aria-pressed="focusMode"
                            >
                                <span class="sr-only">ফোকাস মোড টগল করুন</span>
                                <span
                                    class="inline-block h-6 w-6 transform rounded-full bg-white shadow transition"
                                    :class="focusMode ? 'translate-x-7' : 'translate-x-1'"
                                ></span>
                            </button>
                            <span class="text-xs font-semibold" :class="focusMode ? 'text-emerald-600 dark:text-emerald-300' : 'text-gray-500 dark:text-gray-400'">
                                <span x-text="focusMode ? 'সক্রিয়' : 'বন্ধ'"></span>
                            </span>
                        </div>
                    </div>
                    <div x-show="focusMode" x-transition class="rounded-xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-xs text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                        ফোকাস মোড চালু থাকলে আপনি গুরুত্বপূর্ণ কাজের তালিকা ও অগ্রগতি অগ্রাধিকার হিসেবে দেখতে পারবেন।
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">স্মার্ট অনুস্মারক</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">নতুন প্রশ্ন সেট বা পরীক্ষার তারিখের জন্য অনুস্মারক পেতে পছন্দ করুন।</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="relative inline-flex h-8 w-14 items-center rounded-full transition"
                                :class="reminders ? 'bg-indigo-500/90 shadow shadow-indigo-500/20' : 'bg-gray-200 dark:bg-gray-700'"
                                @click="reminders = !reminders"
                                :aria-pressed="reminders"
                            >
                                <span class="sr-only">স্মার্ট অনুস্মারক টগল করুন</span>
                                <span
                                    class="inline-block h-6 w-6 transform rounded-full bg-white shadow transition"
                                    :class="reminders ? 'translate-x-7' : 'translate-x-1'"
                                ></span>
                            </button>
                            <span class="text-xs font-semibold" :class="reminders ? 'text-indigo-600 dark:text-indigo-300' : 'text-gray-500 dark:text-gray-400'">
                                <span x-text="reminders ? 'চালু' : 'বন্ধ'"></span>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">শেয়ারযোগ্য সারাংশ</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">শিক্ষার্থী বা সহকর্মীদের সাথে ড্যাশবোর্ডের সংক্ষিপ্ত সারাংশ শেয়ার করতে প্রস্তুত রাখুন।</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="relative inline-flex h-8 w-14 items-center rounded-full transition"
                                :class="shareSummary ? 'bg-amber-500/90 shadow shadow-amber-500/20' : 'bg-gray-200 dark:bg-gray-700'"
                                @click="shareSummary = !shareSummary"
                                :aria-pressed="shareSummary"
                            >
                                <span class="sr-only">শেয়ারযোগ্য সারাংশ টগল করুন</span>
                                <span
                                    class="inline-block h-6 w-6 transform rounded-full bg-white shadow transition"
                                    :class="shareSummary ? 'translate-x-7' : 'translate-x-1'"
                                ></span>
                            </button>
                            <span class="text-xs font-semibold" :class="shareSummary ? 'text-amber-600 dark:text-amber-300' : 'text-gray-500 dark:text-gray-400'">
                                <span x-text="shareSummary ? 'প্রস্তুত' : 'অপেক্ষমান'"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">অগ্রগতির সারাংশ</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">ড্যাশবোর্ডের সাম্প্রতিক তথ্যগুলো সংক্ষেপে দেখুন।</p>
                </div>
                <div class="space-y-5 px-6 py-5">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/15 dark:text-indigo-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $questionCount }} টি প্রশ্ন বর্তমানে সক্রিয়</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">বিভিন্ন বিষয় জুড়ে প্রশ্নগুলোর মান বজায় রাখুন।</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/15 dark:text-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 9a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21a9 9 0 1118 0" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $questionSetCount }} টি প্রশ্ন সেট প্রস্তুত রয়েছে</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">প্রতিটি সেট শিক্ষার্থীদের প্রয়োজন অনুযায়ী কাস্টমাইজ করুন।</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/10 text-orange-500 dark:bg-orange-500/15 dark:text-orange-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M18.75 3v11.25A2.25 2.25 0 0116.5 16.5H12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">প্রতি সেটে গড়ে {{ $averageQuestionsPerSet }} টি প্রশ্ন</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">বিষয়ের ভারসাম্য বজায় রাখতে গড় সংখ্যা পর্যবেক্ষণ করুন।</p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-dashed border-gray-200 p-4 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        আরও বিশ্লেষণের জন্য আপনার প্রশ্ন সেটগুলোকে নিয়মিত আপডেট করুন এবং শিক্ষার্থীদের প্রতিক্রিয়া সংগ্রহ করুন।
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">বিষয় কভারেজ</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">প্রশ্নের পরিমাণ অনুসারে সবচেয়ে সক্রিয় বিষয়গুলো দ্রুত পর্যালোচনা করুন।</p>
                </div>
                <div class="space-y-4 px-6 py-5">
                    @forelse ($topSubjects as $subject)
                        @php
                            $subjectPercentage = round(($subject->questions_count / $totalQuestionsForPercentage) * 100);
                        @endphp
                        <div class="space-y-2 rounded-xl border border-gray-100 px-3 py-3 dark:border-gray-800">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-500/10 text-sm font-semibold text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-300">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $subject->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">মোট প্রশ্ন: {{ $subject->questions_count }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-300">{{ $subjectPercentage }}%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                                <div class="h-2 rounded-full bg-indigo-500 dark:bg-indigo-400" style="width: {{ min($subjectPercentage, 100) }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-400">
                            এখনো কোনো বিষয়ের জন্য প্রশ্ন যুক্ত করা হয়নি। নতুন প্রশ্ন যোগ করে বিশ্লেষণ শুরু করুন।
                        </div>
                    @endforelse
                </div>
            </div>

            <div id="resource-center" class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">রিসোর্স সেন্টার</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">পরবর্তী ক্লাস ও প্রশ্ন সেট পরিকল্পনা করতে সহায়ক টুলস ও নির্দেশিকা।</p>
                </div>
                <div class="space-y-4 px-6 py-5">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/15 dark:text-indigo-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 016 4.5h12a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0118 19.5H6a2.25 2.25 0 01-2.25-2.25V6.75z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6m-6 3h3" />
                            </svg>
                        </span>
                        <div class="flex-1 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">মূল্যায়ন রুব্রিক টেমপ্লেট</p>
                                <span class="inline-flex items-center gap-1 rounded-full bg-indigo-500/10 px-2 py-0.5 text-[10px] font-semibold uppercase text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-300">PDF</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">প্রশ্নের মান নির্ধারণ ও মূল্যায়নের জন্য দলীয় টেমপ্লেট আপডেট রাখুন।</p>
                            <div class="inline-flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-300">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span>ডাউনলোড লিংক টিম ড্রাইভে যুক্ত রয়েছে</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/15 dark:text-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M3 9h18m-8.25 4.5h8.25M3 13.5h5.25M3 18h12.75" />
                            </svg>
                        </span>
                        <div class="flex-1 space-y-2">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">লাইভ ক্লাস চেকলিস্ট</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">প্র্যাকটিস সেশন শুরুর আগে দ্রুত প্রস্তুতির জন্য তালিকাটি ব্যবহার করুন।</p>
                            <a
                                wire:navigate
                                href="{{ route('practice') }}"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 transition hover:text-emerald-500 dark:text-emerald-300 dark:hover:text-emerald-200"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 12h13.5m0 0L12 18.75M18.75 12L12 5.25" />
                                </svg>
                                <span>প্র্যাকটিস সেশনে যান</span>
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-500 dark:bg-amber-500/15 dark:text-amber-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75A2.25 2.25 0 016.75 4.5h10.5A2.25 2.25 0 0119.5 6.75v10.5A2.25 2.25 0 0117.25 19.5H6.75A2.25 2.25 0 014.5 17.25V6.75z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9h7.5m-7.5 3h4.5" />
                            </svg>
                        </span>
                        <div class="flex-1 space-y-2">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">ড্যাশবোর্ড নোট</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">আজকের লক্ষ্য বা অনুস্মারক লিখে রাখার জন্য নিচের বোতামটি ব্যবহার করুন।</p>
                            <div x-data="{ copied: false }" class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-medium text-amber-600 transition hover:border-amber-300 hover:bg-amber-50 dark:border-amber-500/30 dark:text-amber-300 dark:hover:border-amber-400/40 dark:hover:bg-amber-500/10"
                                    @click="copied = true; navigator.clipboard && navigator.clipboard.writeText('আজকের লক্ষ্য: নতুন প্রশ্ন সেট তৈরি করুন এবং শিক্ষার্থীদের জন্য প্র্যাকটিস শেয়ার করুন।'); setTimeout(() => copied = false, 2000);"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9.75h.008v.008H9V9.75zM9 12.75h.008v.008H9v-.008zM9 15.75h.008v.008H9v-.008z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5A1.5 1.5 0 014.5 3h9A1.5 1.5 0 0115 4.5v15l-3-1.5-3 1.5v-15zM18 6.75h1.5A1.5 1.5 0 0121 8.25v12.75l-3-1.5-3 1.5V8.25a1.5 1.5 0 011.5-1.5H18z" />
                                    </svg>
                                    <span>নোট কপি করুন</span>
                                </button>
                                <span x-show="copied" style="display: none;" x-transition class="text-xs font-semibold text-amber-600 dark:text-amber-300">কপি সম্পন্ন!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', () => {
            const subjectChartData = {
                categories: @json($subjects->pluck('name')),
                series: @json($subjects->pluck('questions_count')),
            };

            const activityChartData = {
                categories: @json($activityTimeline->pluck('label')),
                questions: @json($activityTimeline->pluck('questions')),
                questionSets: @json($activityTimeline->pluck('question_sets')),
            };

            const resolveMode = () => (document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            const resolveThemePalette = mode => ({
                axis: mode === 'dark' ? '#9CA3AF' : '#6B7280',
                text: mode === 'dark' ? '#E5E7EB' : '#111827',
                grid: mode === 'dark' ? '#374151' : '#E5E7EB',
                background: mode === 'dark' ? '#111827' : '#FFFFFF',
            });

            const charts = [];
            const noDataText = 'ডেটা প্রদর্শনের জন্য তথ্য নেই';

            const registerChart = (selector, optionsFactory) => {
                const element = typeof selector === 'string' ? document.querySelector(selector) : selector;

                if (!element) {
                    return;
                }

                if (element.__apexChartInstance) {
                    element.__apexChartInstance.destroy();
                    element.__apexChartInstance = null;
                }

                const existingIndex = charts.findIndex(entry => entry.element === element);
                if (existingIndex !== -1) {
                    charts[existingIndex].chart.destroy();
                    charts.splice(existingIndex, 1);
                }

                const chart = new ApexCharts(element, optionsFactory(resolveMode()));
                chart.render();
                element.__apexChartInstance = chart;

                charts.push({ element, chart, optionsFactory });
            };

            const updateChartsTheme = mode => {
                for (let index = charts.length - 1; index >= 0; index--) {
                    const entry = charts[index];

                    if (!document.body.contains(entry.element)) {
                        entry.chart.destroy();
                        charts.splice(index, 1);
                        continue;
                    }

                    entry.chart.updateOptions(entry.optionsFactory(mode));
                }
            };

            const initializeCharts = () => {
                registerChart('#subjectChart', mode => {
                    const palette = resolveThemePalette(mode);

                    return {
                        chart: {
                            type: 'bar',
                            height: 320,
                            toolbar: { show: false },
                            fontFamily: 'inherit',
                        },
                        series: [
                            {
                                name: 'প্রশ্ন সংখ্যা',
                                data: subjectChartData.series,
                            },
                        ],
                        xaxis: {
                            categories: subjectChartData.categories,
                            labels: {
                                style: {
                                    colors: palette.axis,
                                },
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: palette.axis,
                                },
                            },
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '45%',
                                borderRadius: 6,
                                distributed: true,
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                colors: [palette.text],
                                fontSize: '12px',
                            },
                        },
                        colors: ['#6366F1', '#8B5CF6', '#22C55E', '#F97316', '#EC4899', '#0EA5E9'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 0.4,
                                opacityFrom: 0.95,
                                opacityTo: 0.85,
                                stops: [0, 80, 100],
                            },
                        },
                        grid: {
                            borderColor: palette.grid,
                            strokeDashArray: 6,
                            xaxis: { lines: { show: false } },
                        },
                        tooltip: {
                            theme: mode,
                        },
                        theme: {
                            mode,
                        },
                        noData: {
                            text: noDataText,
                            align: 'center',
                            verticalAlign: 'middle',
                            style: {
                                color: palette.text,
                                fontFamily: 'inherit',
                            },
                        },
                    };
                });

                registerChart('#activityChart', mode => {
                    const palette = resolveThemePalette(mode);

                    return {
                        chart: {
                            type: 'area',
                            height: 320,
                            toolbar: { show: false },
                            fontFamily: 'inherit',
                        },
                        series: [
                            {
                                name: 'প্রশ্ন',
                                data: activityChartData.questions,
                            },
                            {
                                name: 'প্রশ্ন সেট',
                                data: activityChartData.questionSets,
                            },
                        ],
                        xaxis: {
                            categories: activityChartData.categories,
                            labels: {
                                style: {
                                    colors: palette.axis,
                                },
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                        },
                        yaxis: {
                            min: 0,
                            labels: {
                                style: {
                                    colors: palette.axis,
                                },
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 0.35,
                                opacityFrom: 0.9,
                                opacityTo: 0.25,
                                stops: [0, 90, 100],
                            },
                        },
                        colors: ['#6366F1', '#22C55E'],
                        markers: {
                            size: 4,
                            strokeColors: palette.background,
                            strokeWidth: 2,
                        },
                        grid: {
                            borderColor: palette.grid,
                            strokeDashArray: 6,
                            xaxis: { lines: { show: false } },
                        },
                        legend: {
                            labels: {
                                colors: palette.text,
                            },
                        },
                        tooltip: {
                            theme: mode,
                        },
                        theme: {
                            mode,
                        },
                        noData: {
                            text: noDataText,
                            align: 'center',
                            verticalAlign: 'middle',
                            style: {
                                color: palette.text,
                                fontFamily: 'inherit',
                            },
                        },
                    };
                });
            };

            initializeCharts();

            const themeListener = event => {
                const mode = event.detail?.mode ?? resolveMode();
                updateChartsTheme(mode);
            };

            if (window.__teacherDashboardThemeListener) {
                window.removeEventListener('theme-changed', window.__teacherDashboardThemeListener);
            }

            window.__teacherDashboardThemeListener = themeListener;
            window.addEventListener('theme-changed', themeListener);

            const navigatedListener = () => {
                initializeCharts();
                updateChartsTheme(resolveMode());
            };

            if (window.__teacherDashboardNavigatedListener) {
                document.removeEventListener('livewire:navigated', window.__teacherDashboardNavigatedListener);
            }

            window.__teacherDashboardNavigatedListener = navigatedListener;
            document.addEventListener('livewire:navigated', navigatedListener);
        });
    </script>
@endpush
