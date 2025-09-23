<div class="space-y-8">
    @php
        $user = auth()->user();
        $averageQuestionsPerSet = $questionSetCount > 0
            ? number_format($questionCount / max($questionSetCount, 1), 1)
            : '0.0';
        $recentQuestionSet = $questionSets->first();
        $activeSubjects = $subjects->where('questions_count', '>', 0)->count();
    @endphp

    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-emerald-500 p-8 text-white shadow-xl">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -right-12 -top-12 h-56 w-56 rounded-full bg-white/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-12 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        </div>

        <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="space-y-3">
                <p class="text-sm uppercase tracking-widest text-white/70">ড্যাশবোর্ড</p>
                <h1 class="text-3xl font-semibold md:text-4xl">স্বাগতম, {{ $user->name }}!</h1>
                <p class="max-w-2xl text-sm md:text-base text-white/80">
                    আপনার তৈরি করা প্রশ্ন ও প্রশ্ন সেটগুলোর কার্যকারিতা বিশ্লেষণ করুন, দ্রুত নতুন কনটেন্ট তৈরি করুন এবং শিক্ষার্থীদের জন্য সর্বোত্তম প্রস্তুতি নিশ্চিত করুন।
                </p>

                <div class="flex flex-wrap gap-4 text-sm text-white/80">
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
                </div>
            </div>

            <div class="flex flex-col items-start gap-4 rounded-2xl bg-white/10 p-6 backdrop-blur">
                <div>
                    <p class="text-xs uppercase tracking-widest text-white/70">সর্বশেষ প্রশ্ন সেট</p>
                    <p class="mt-1 text-lg font-semibold">
                        {{ $recentQuestionSet?->name ?? 'এখনো তৈরি করা হয়নি' }}
                    </p>
                </div>

                <a
                    wire:navigate
                    href="{{ route('questions.set.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-emerald-600 shadow hover:bg-emerald-50"
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

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
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
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
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
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', () => {
            const chartElement = document.querySelector('#subjectChart');

            if (!chartElement) {
                return;
            }

            const chartData = {
                categories: {!! json_encode($subjects->pluck('name')) !!},
                series: {!! json_encode($subjects->pluck('questions_count')) !!},
            };

            const isDarkMode = document.documentElement.classList.contains('dark');

            const chart = new ApexCharts(chartElement, {
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'inherit',
                },
                series: [{ name: 'Questions', data: chartData.series }],
                xaxis: {
                    categories: chartData.categories,
                    labels: {
                        style: {
                            colors: isDarkMode ? '#9CA3AF' : '#6B7280',
                        },
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: isDarkMode ? '#9CA3AF' : '#6B7280',
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
                        colors: [isDarkMode ? '#E5E7EB' : '#111827'],
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
                    borderColor: isDarkMode ? '#374151' : '#E5E7EB',
                    strokeDashArray: 6,
                    xaxis: { lines: { show: false } },
                },
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light',
                },
                theme: {
                    mode: isDarkMode ? 'dark' : 'light',
                },
            });

            chart.render();
        });
    </script>
@endpush
