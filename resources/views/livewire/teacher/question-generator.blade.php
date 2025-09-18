<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">প্রশ্ন ক্রিয়েট</h1>
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
            নিচের ফর্ম পূরণ করে পরীক্ষার জন্য প্রয়োজনীয় শর্ত নির্বাচন করুন এবং নমুনা প্রশ্ন তৈরি করুন।
        </p>

        @if($notification)
            <div @class([
                'mb-6 rounded-lg border px-4 py-3 text-sm flex items-start gap-3',
                'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-200' => $notification['type'] === 'warning',
                'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200' => $notification['type'] === 'success',
            ])>
                <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    @if($notification['type'] === 'warning')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 .75a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @endif
                </svg>
                <span>{{ $notification['message'] }}</span>
            </div>
        @endif

        <form wire:submit.prevent="generateQuestions" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="examName">পরীক্ষার নাম</label>
                    <input id="examName" type="text" wire:model.defer="examName"
                           class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="যেমন: মাসিক মূল্যায়ন" />
                    @error('examName')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="subject">বিষয় নির্বাচন</label>
                    <select id="subject" wire:model="subjectId"
                            class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">বিষয় নির্বাচন করুন</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subjectId')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="subSubject">সাব-বিষয়</label>
                    <select id="subSubject" wire:model="subSubjectId"
                            class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                            @disabled(empty($subSubjects))>
                        <option value="">সাব-বিষয় নির্বাচন করুন</option>
                        @foreach($subSubjects as $subSubject)
                            <option value="{{ $subSubject['id'] }}">{{ $subSubject['name'] }}</option>
                        @endforeach
                    </select>
                    @error('subSubjectId')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="chapter">অধ্যায়</label>
                    <select id="chapter" wire:model="chapterId"
                            class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                            @disabled(empty($chapters))>
                        <option value="">সমস্ত অধ্যায়</option>
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter['id'] }}">{{ $chapter['name'] }}</option>
                        @endforeach
                    </select>
                    @error('chapterId')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">প্রশ্নের টাইপ</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($typeOptions as $value => $label)
                            <label @class([
                                'inline-flex items-center gap-2 px-3 py-1.5 border rounded-lg cursor-pointer text-sm transition',
                                'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-900/30 dark:text-indigo-100' => $questionType === $value,
                                'border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300' => $questionType !== $value,
                            ])>
                                <input type="radio" class="text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                       value="{{ $value }}" wire:model="questionType">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('questionType')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="questionCount">প্রশ্নের সংখ্যা</label>
                    <input id="questionCount" type="number" min="1" max="50" wire:model="questionCount"
                           class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('questionCount')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2.5 rounded-lg shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487z" />
                    </svg>
                    প্রশ্ন তৈরী করুন
                </button>
            </div>
        </form>
    </div>

    @if($showGenerationResults)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">নমুনা প্রশ্ন</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">সিলেক্ট করে সেভ করুন।</p>
                </div>
                <span class="text-sm text-gray-500">{{ count($generatedQuestions) }} টি প্রশ্ন পাওয়া গেছে</span>
            </div>

            <form wire:submit.prevent="saveSelection" class="space-y-4">
                @php
                    $difficultyLabels = ['easy' => 'সহজ', 'medium' => 'মাঝারি', 'hard' => 'কঠিন'];
                @endphp

                <div class="space-y-4">
                    @forelse($generatedQuestions as $question)
                        <label class="flex items-start gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-indigo-300 dark:hover:border-indigo-400 transition">
                            <input type="checkbox" value="{{ $question['id'] }}" wire:model="selectedQuestionIds"
                                   class="mt-1 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <div class="space-y-2">
                                <div class="text-gray-800 dark:text-gray-100 prose prose-sm max-w-none dark:prose-invert">
                                    {!! $question['title'] !!}
                                </div>
                                <div class="flex flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    @if($question['chapter'])
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-200 rounded-full">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h13.5M3 9h9m-9 9h13.5m-13.5-4.5h9m5.25-9L21 6.75 17.25 9" />
                                            </svg>
                                            {{ $question['chapter'] }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-full">
                                        {{ __('কঠিনতা') }}: {{ $difficultyLabels[$question['difficulty']] ?? ucfirst($question['difficulty']) }}
                                    </span>
                                    @foreach($question['tags'] as $tag)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-full">#{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                            নির্বাচিত শর্তে কোনো প্রশ্ন পাওয়া যায়নি।
                        </div>
                    @endforelse
                </div>

                @error('selectedQuestionIds')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror

                <div class="flex items-center justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-5 py-2.5 rounded-lg shadow">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        প্রশ্ন সেভ করুন
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if($questionPaperSummary)
        @php
            $isMcqPaper = ($questionPaperSummary['type_key'] ?? null) === 'mcq';
            $optionLabels = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ'];
        @endphp

        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-lg p-6 space-y-4">
            <div class="flex items-center gap-3 text-emerald-700 dark:text-emerald-200 no-print">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m5.25 2.25a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="text-lg font-semibold">প্রশ্নপত্র প্রস্তুত হয়েছে!</h3>
                    <p class="text-sm">সিলেক্ট করা প্রশ্নগুলো দিয়ে নতুন প্রশ্নপত্র তৈরি হয়েছে। প্রয়োজনে ডাউনলোড বা শেয়ার করুন।</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-200">
                <div><span class="font-medium">পরীক্ষার নাম:</span> {{ $questionPaperSummary['exam_name'] }}</div>
                <div><span class="font-medium">বিষয়:</span> {{ $questionPaperSummary['subject'] }}</div>
                <div><span class="font-medium">সাব-বিষয়:</span> {{ $questionPaperSummary['sub_subject'] }}</div>
                <div><span class="font-medium">অধ্যায়:</span> {{ $questionPaperSummary['chapter'] }}</div>
                <div><span class="font-medium">প্রশ্নের টাইপ:</span> {{ $questionPaperSummary['type'] }}</div>
                <div><span class="font-medium">মোট প্রশ্ন:</span> {{ $questionPaperSummary['total_questions'] }}</div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg border border-emerald-100 dark:border-emerald-800 p-0 md:p-4">
                <div class="p-4 pb-0 border-b border-emerald-100 dark:border-emerald-800">
                    <h4 class="text-sm font-medium text-emerald-700 dark:text-emerald-200">নির্বাচিত প্রশ্নসমূহ</h4>
                </div>

                <div class="question-paper-preview text-gray-800 dark:text-gray-100">
                    <div class="question-paper-header text-center border-b border-emerald-100 dark:border-emerald-800 px-6 py-4">
                        <h2 class="text-xl font-semibold">{{ $questionPaperSummary['exam_name'] }}</h2>
                        <div class="mt-2 text-sm space-y-1">
                            <div><span class="font-medium">বিষয়:</span> {{ $questionPaperSummary['subject'] }}</div>
                            <div><span class="font-medium">সাব-বিষয়:</span> {{ $questionPaperSummary['sub_subject'] }}</div>
                            <div><span class="font-medium">অধ্যায়:</span> {{ $questionPaperSummary['chapter'] }}</div>
                            <div><span class="font-medium">প্রশ্নের টাইপ:</span> {{ $questionPaperSummary['type'] }}</div>
                            <div><span class="font-medium">মোট প্রশ্ন:</span> {{ $questionPaperSummary['total_questions'] }}</div>
                        </div>
                    </div>

                    <div class="question-paper-body px-6 py-6">
                        <ol class="question-paper-list">
                            @foreach($questionPaperSummary['questions'] as $index => $question)
                                <li class="question-item">
                                    <div class="question-number">{{ $index + 1 }}.</div>
                                    <div class="question-content">
                                        <div class="prose prose-sm max-w-none dark:prose-invert">
                                            {!! $question['title'] !!}
                                        </div>

                                        @if($isMcqPaper && !empty($question['options']))
                                            <ul class="question-options">
                                                @foreach($question['options'] as $optIndex => $option)
                                                    <li class="question-option">
                                                        <span class="option-label">{{ $optionLabels[$optIndex] ?? ($optIndex + 1) }}.</span>
                                                        <span class="option-text">{!! $option !!}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @if($question['chapter'])
                                            <span class="question-chip">{{ $question['chapter'] }}</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 no-print">
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-lg shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5m-7.5 3h7.5m-7.5 3h7.5M5.25 19.5h13.5A1.5 1.5 0 0020.25 18V6a1.5 1.5 0 00-1.5-1.5H5.25A1.5 1.5 0 003.75 6v12a1.5 1.5 0 001.5 1.5z" />
                    </svg>
                    প্রশ্ন প্রিন্ট করুন
                </button>
                <button type="button" class="inline-flex items-center gap-2 bg-white dark:bg-transparent border border-emerald-400 text-emerald-700 dark:text-emerald-200 px-4 py-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-1.125 0h11.25m-10.125 3.75h9" />
                    </svg>
                    প্রশ্ন মুক্ত করুন
                </button>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        .question-paper-preview {
            background: #ffffff;
        }

        .question-paper-body {
            column-count: 1;
            column-gap: 2.5rem;
        }

        .question-paper-list {
            list-style: none;
            margin: 0;
            padding: 0;
            column-count: 1;
            column-gap: 2.5rem;
        }

        .question-item {
            break-inside: avoid;
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .question-number {
            font-weight: 600;
            min-width: 1.75rem;
        }

        .question-options {
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
            padding-left: 0;
            list-style: none;
        }

        .question-option {
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .option-label {
            font-weight: 600;
            min-width: 1.5rem;
        }

        .question-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            background-color: #d1fae5;
            color: #047857;
        }

        @media (min-width: 768px) {
            .question-paper-body,
            .question-paper-list {
                column-count: 2;
            }
        }

        @media print {
            body {
                background: #ffffff !important;
            }

            @page {
                size: A4;
                margin: 15mm;
            }

            .no-print {
                display: none !important;
            }

            .question-paper-preview {
                border: none !important;
                box-shadow: none !important;
            }

            .question-paper-body,
            .question-paper-list {
                column-count: 2;
                column-gap: 18mm;
            }

            .question-item {
                break-inside: avoid-column;
            }

            .question-chip {
                background-color: #d1fae5 !important;
                color: #047857 !important;
            }
        }
    </style>
@endpush
