<div class="space-y-6">
    @php
        $teacher = auth()->user();
        $selectedSubject = $subjects->firstWhere('id', $subjectId);
        $selectedSubSubject = collect($subSubjects)->firstWhere('id', $subSubjectId);
        $selectedChapter = collect($chapters)->firstWhere('id', $chapterId);
        $programDisplayName = $programName ?: ($teacher?->institution_name ?: $teacher?->name ?: __('প্রতিষ্ঠানের নাম অনুপলব্ধ'));
    @endphp

    @if($notification)
        <div @class([
            'rounded-lg border px-4 py-3 text-sm flex items-start gap-3 shadow-sm',
            'border-amber-200 bg-amber-50 text-amber-700' => $notification['type'] === 'warning',
            'border-emerald-200 bg-emerald-50 text-emerald-700' => $notification['type'] === 'success',
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

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-col gap-2 mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('প্রশ্ন তৈরী করুন') }}</h1>
            <p class="text-sm text-gray-600">{{ __('নিচের ফর্ম পূরণ করে পছন্দমতো সেটিংস নির্বাচন করুন এবং প্রশ্ন জেনারেট করুন।') }}</p>
        </div>

        <form id="question-generator-form" wire:submit.prevent="generateQuestions" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="examName">{{ __('পরীক্ষার নাম') }}</label>
                    <input
                        id="examName"
                        type="text"
                        wire:model.defer="examName"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('যেমন: সাপ্তাহিক মূল্যায়ন') }}"
                    />
                    @error('examName')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="subject">{{ __('বিষয় নির্বাচন') }}</label>
                    <select
                        id="subject"
                        wire:model.live="subjectId"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">{{ __('বিষয় নির্বাচন করুন') }}</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subjectId')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="subSubject">{{ __('সাব-বিষয়') }}</label>
                    <select
                        id="subSubject"
                        wire:model.live="subSubjectId"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        @disabled(empty($subSubjects))
                    >
                        <option value="">{{ __('সকল সাব-বিষয়') }}</option>
                        @foreach($subSubjects as $subSubject)
                            <option value="{{ $subSubject['id'] }}">{{ $subSubject['name'] }}</option>
                        @endforeach
                    </select>
                    @error('subSubjectId')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="chapter">{{ __('অধ্যায়') }}</label>
                    <select
                        id="chapter"
                        wire:model.live="chapterId"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        @disabled(empty($chapters))
                    >
                        <option value="">{{ __('সমস্ত অধ্যায়') }}</option>
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter['id'] }}">{{ $chapter['name'] }}</option>
                        @endforeach
                    </select>
                    @error('chapterId')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('প্রশ্নের টাইপ') }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($typeOptions as $value => $label)
                            <label @class([
                                'inline-flex items-center gap-2 px-3 py-1.5 border rounded-lg cursor-pointer text-sm transition',
                                'border-indigo-500 bg-indigo-50 text-indigo-700' => $questionType === $value,
                                'border-gray-200 text-gray-600' => $questionType !== $value,
                            ])>
                                <input
                                    type="radio"
                                    class="text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                    value="{{ $value }}"
                                    wire:model="questionType"
                                >
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('questionType')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="questionCount">{{ __('প্রশ্নের সংখ্যা') }}</label>
                    <input
                        id="questionCount"
                        type="number"
                        min="1"
                        max="50"
                        wire:model="questionCount"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('questionCount')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-5 border-t border-gray-100">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="programName">{{ __('প্রতিষ্ঠান / প্রোগ্রামের নাম') }}</label>
                    <input
                        id="programName"
                        type="text"
                        wire:model.defer="programName"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('যেমন: ডিজিটাল কোচিং হোম') }}"
                    />
                    @error('programName')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="classLevel">{{ __('শ্রেণি / লেভেল') }}</label>
                    <input
                        id="classLevel"
                        type="text"
                        wire:model.defer="classLevel"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('যেমন: নবম / দশম') }}"
                    />
                    @error('classLevel')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="setCode">{{ __('সেট কোড') }}</label>
                    <input
                        id="setCode"
                        type="text"
                        wire:model.defer="setCode"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('যেমন: ক') }}"
                    />
                    @error('setCode')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="duration">{{ __('সময়') }}</label>
                    <input
                        id="duration"
                        type="text"
                        wire:model.defer="duration"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('যেমন: ৪০ মিনিট') }}"
                    />
                    @error('duration')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="totalMarks">{{ __('পূর্ণমান') }}</label>
                    <input
                        id="totalMarks"
                        type="text"
                        wire:model.defer="totalMarks"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('যেমন: ২৫') }}"
                    />
                    @error('totalMarks')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="instructionText">{{ __('নির্দেশনা') }}</label>
                    <textarea
                        id="instructionText"
                        rows="3"
                        wire:model.defer="instructionText"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    @error('instructionText')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="noticeText">{{ __('বিশেষ ঘোষণা') }}</label>
                    <textarea
                        id="noticeText"
                        rows="3"
                        wire:model.defer="noticeText"
                        class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    @error('noticeText')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2.5 rounded-lg shadow"
                    wire:loading.attr="disabled"
                    wire:target="generateQuestions"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487z" />
                    </svg>
                    <span wire:loading.remove wire:target="generateQuestions">{{ __('প্রশ্ন তৈরী করুন') }}</span>
                    <span wire:loading.flex wire:target="generateQuestions" class="gap-2 items-center hidden">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        {{ __('প্রসেস হচ্ছে...') }}
                    </span>
                </button>
            </div>
        </form>
    </div>

    @if($showGenerationResults)
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6 rounded-xl border border-dashed border-indigo-300 bg-indigo-50/50 p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-indigo-600">{{ $programDisplayName }}</p>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $examName ?: __('পরীক্ষার নাম নির্ধারিত হয়নি') }}</h2>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p>{{ __('বিষয়') }}: {{ $selectedSubject->name ?? __('নির্বাচিত হয়নি') }}</p>
                            <p>{{ __('সাব-বিষয়') }}: {{ $selectedSubSubject['name'] ?? __('নির্বাচিত হয়নি') }}</p>
                            @if($selectedChapter)
                                <p>{{ __('অধ্যায়') }}: {{ $selectedChapter['name'] }}</p>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-gray-600">
                            <span>{{ __('সময়') }}: {{ $duration ?: __('নির্ধারিত নয়') }}</span>
                            <span>{{ __('পূর্ণমান') }}: {{ $totalMarks ?: __('নির্ধারিত নয়') }}</span>
                            <span>{{ __('মোট প্রশ্ন') }}: {{ $questionCount }}</span>
                        </div>
                    </div>
                    <div class="md:w-64">
                        <button
                            type="button"
                            class="w-full rounded-lg border border-indigo-400 bg-white px-4 py-2 text-indigo-600 font-medium hover:bg-indigo-50 transition"
                            onclick="document.getElementById('question-generator-form')?.scrollIntoView({behavior: 'smooth'});"
                        >
                            {{ __('প্রশ্ন সংযোজন করতে এখানে ক্লিক করুন') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('ডাটাবেইজ থেকে প্রস্তাবিত প্রশ্ন') }}</h2>
                    <p class="text-sm text-gray-600">{{ __('পছন্দের প্রশ্ন নির্বাচন করে নিচে সেভ করুন।') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600" for="sortOption">{{ __('সোর্ট অপশন') }}</label>
                    <select
                        id="sortOption"
                        wire:model="sortOption"
                        class="rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <span class="text-sm text-gray-500">{{ __('মোট') }} {{ count($generatedQuestions) }} {{ __('টি প্রশ্ন পাওয়া গেছে') }}</span>

            <form wire:submit.prevent="saveSelection" class="mt-4 space-y-4">
                @php
                    $difficultyLabels = ['easy' => __('সহজ'), 'medium' => __('মাঝারি'), 'hard' => __('কঠিন')];
                @endphp

                <div class="space-y-4">
                    @forelse($generatedQuestions as $question)
                        <label
                            class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg hover:border-indigo-300 transition"
                            wire:key="question-{{ $question['id'] }}"
                        >
                            <input
                                type="checkbox"
                                value="{{ $question['id'] }}"
                                wire:model="selectedQuestionIds"
                                class="mt-1 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <div class="space-y-2">
                                <div class="text-gray-800 prose prose-sm max-w-none">
                                    {!! $question['title'] !!}
                                </div>
                                <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                                    @if($question['chapter'])
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 text-indigo-600 rounded-full">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h13.5M3 9h9m-9 9h13.5m-13.5-4.5h9m5.25-9L21 6.75 17.25 9" />
                                            </svg>
                                            {{ $question['chapter'] }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded-full">
                                        {{ __('কঠিনতা') }}: {{ $difficultyLabels[$question['difficulty']] ?? ucfirst($question['difficulty']) }}
                                    </span>
                                    @foreach($question['tags'] as $tag)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full">#{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="text-center py-10 text-gray-500">{{ __('নির্বাচিত সেটিংস অনুযায়ী কোনো প্রশ্ন পাওয়া যায়নি।') }}</div>
                    @endforelse
                </div>

                @error('selectedQuestionIds')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror

                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <p class="text-sm text-gray-600">{{ __('সিলেক্ট করা প্রশ্ন') }}: {{ count($selectedQuestionIds) }}</p>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-5 py-2.5 rounded-lg shadow"
                        wire:loading.attr="disabled"
                        wire:target="saveSelection"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 .75a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span wire:loading.remove wire:target="saveSelection">{{ __('সেইভ করুন') }}</span>
                        <span wire:loading.flex wire:target="saveSelection" class="gap-2 items-center hidden">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            {{ __('সেভ হচ্ছে...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if($questionPaperSummary)
        @php
            $summary = $questionPaperSummary;
            $isMcqPaper = ($summary['type_key'] ?? null) === 'mcq';
            $optionLabels = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ'];
        @endphp

        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('প্রস্তুত প্রশ্নপত্র') }}</h2>
                    <p class="text-sm text-gray-600">{{ __('সেটিংস অনুযায়ী প্রস্তুতকৃত প্রশ্নগুলো নিচে দেখুন।') }}</p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center gap-2 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 font-medium px-4 py-2 rounded-lg"
                    wire:click="$toggle('showPreview')"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $showPreview ? __('পূর্বরূপ বন্ধ করুন') : __('ভিউ করুন') }}
                </button>
            </div>

            @if($showPreview)
                <div class="border border-gray-200 rounded-lg p-6 space-y-6">
                    <div class="text-center space-y-1">
                        <p class="text-sm text-gray-500">{{ $summary['program_name'] }}</p>
                        <h3 class="text-2xl font-semibold text-gray-900">{{ $summary['exam_name'] }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ $summary['subject'] }}
                            @if(!empty($summary['sub_subject']))
                                · {{ $summary['sub_subject'] }}
                            @endif
                            @if(!empty($summary['chapter']))
                                · {{ $summary['chapter'] }}
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-gray-600">
                        @if(!empty($summary['class_level']))
                            <span>{{ __('শ্রেণি') }}: {{ $summary['class_level'] }}</span>
                        @endif
                        @if(!empty($summary['set_code']))
                            <span>{{ __('সেট কোড') }}: {{ $summary['set_code'] }}</span>
                        @endif
                        @if(!empty($summary['duration']))
                            <span>{{ __('সময়') }}: {{ $summary['duration'] }}</span>
                        @endif
                        @if(!empty($summary['total_marks']))
                            <span>{{ __('পূর্ণমান') }}: {{ $summary['total_marks'] }}</span>
                        @endif
                        <span>{{ __('মোট প্রশ্ন') }}: {{ $summary['total_questions'] }}</span>
                    </div>

                    @if(!empty($summary['instruction_text']))
                        <div class="bg-indigo-50 text-indigo-700 text-sm rounded-lg px-4 py-3">
                            {!! nl2br(e($summary['instruction_text'])) !!}
                        </div>
                    @endif

                    <ol class="space-y-4 text-gray-800 list-decimal list-inside">
                        @foreach($summary['questions'] as $index => $question)
                            <li class="space-y-2">
                                <div class="prose prose-sm max-w-none">
                                    {!! $question['title'] !!}
                                </div>
                                @if($isMcqPaper && ! empty($question['options']))
                                    <ul class="grid gap-2 md:grid-cols-2 text-sm text-gray-600">
                                        @foreach($question['options'] as $optionIndex => $option)
                                            <li class="flex gap-2">
                                                <span class="font-semibold text-indigo-600">{{ $optionLabels[$optionIndex] ?? ($optionIndex + 1) }}.</span>
                                                <span class="prose prose-sm max-w-none">{!! $option !!}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ol>

                    @if(!empty($summary['notice_text']))
                        <div class="bg-amber-50 text-amber-700 text-sm rounded-lg px-4 py-3">
                            {!! nl2br(e($summary['notice_text'])) !!}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
