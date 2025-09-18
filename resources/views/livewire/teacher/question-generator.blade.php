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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-5 border-t border-gray-100 dark:border-gray-700">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="programName">প্রতিষ্ঠান / প্রোগ্রামের নাম</label>
                    <input id="programName" type="text" wire:model.defer="programName"
                           class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="যেমন: ডিজিটাল কোচিং হোম" />
                    @error('programName')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="classLevel">শ্রেণি / লেভেল</label>
                    <input id="classLevel" type="text" wire:model.defer="classLevel"
                           class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="যেমন: নবম / দশম" />
                    @error('classLevel')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="setCode">সেট কোড</label>
                    <input id="setCode" type="text" wire:model.defer="setCode"
                           class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="যেমন: ক" />
                    @error('setCode')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="duration">সময়</label>
                    <input id="duration" type="text" wire:model.defer="duration"
                           class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="যেমন: ৩০ মিনিট" />
                    @error('duration')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="totalMarks">পূর্ণমান</label>
                    <input id="totalMarks" type="text" wire:model.defer="totalMarks"
                           class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="যেমন: ২০" />
                    @error('totalMarks')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="instructionText">নির্দেশনা</label>
                    <textarea id="instructionText" rows="3" wire:model.defer="instructionText"
                              class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('instructionText')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1" for="noticeText">বিশেষ ঘোষণা</label>
                    <textarea id="noticeText" rows="3" wire:model.defer="noticeText"
                              class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('noticeText')
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
            use Illuminate\Support\Str;

            $isMcqPaper = ($questionPaperSummary['type_key'] ?? null) === 'mcq';
            $optionLabels = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ'];
            $summary = $questionPaperSummary;
            $textAlignClassMap = [
                'left' => 'text-left',
                'center' => 'text-center',
                'right' => 'text-right',
                'justify' => 'text-justify',
            ];
            $columnClassMap = [
                1 => 'columns-1',
                2 => 'columns-1 md:columns-2',
                3 => 'columns-1 md:columns-3',
            ];
            $fontStacks = [
                'Bangla' => "'Shurjo', 'Noto Sans Bengali', 'SolaimanLipi', sans-serif",
                'SolaimanLipi' => "'SolaimanLipi', 'Shurjo', 'Noto Sans Bengali', sans-serif",
                'Kalpurush' => "'Kalpurush', 'Shurjo', 'Noto Sans Bengali', sans-serif",
                'roman' => "'Times New Roman', serif",
            ];
            $textAlignClass = $textAlignClassMap[$textAlign] ?? 'text-justify';
            $columnClass = $columnClassMap[$columnCount] ?? 'columns-1 md:columns-2';
            $fontStack = $fontStacks[$fontFamily] ?? $fontStacks['Bangla'];
            $paperSizeKey = strtolower($paperSize);
            $optionLabelClasses = [
                'circle' => 'flex h-7 w-7 items-center justify-center rounded-full border border-cyan-600 text-sm font-semibold text-cyan-700',
                'dot' => 'flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-700',
                'parentheses' => 'text-sm font-semibold text-cyan-700',
                'minimal' => 'text-sm font-semibold text-slate-600',
            ];
        @endphp

        <div class="space-y-6">
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-6 text-emerald-700 shadow-sm dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200 print:hidden">
                <div class="flex items-start gap-3">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m5.25 2.25a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="space-y-1">
                        <h3 class="text-lg font-semibold">প্রশ্নপত্র প্রস্তুত হয়েছে!</h3>
                        <p class="text-sm text-emerald-600/80 dark:text-emerald-100/80">সিলেক্ট করা প্রশ্নগুলো দিয়ে নতুন প্রশ্নপত্র তৈরি হয়েছে। প্রয়োজনে ডাউনলোড বা শেয়ার করুন।</p>
                    </div>
                </div>
                <div class="mt-4 grid gap-3 text-sm text-emerald-800 dark:text-emerald-100 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="flex items-center gap-2 rounded-xl border border-emerald-200/60 bg-white/60 px-3 py-2 dark:border-emerald-700/70 dark:bg-emerald-900/40"><span class="text-xs font-semibold uppercase tracking-widest text-emerald-500 dark:text-emerald-300">পরীক্ষা</span><span class="font-medium text-emerald-700 dark:text-emerald-100">{{ $summary['exam_name'] }}</span></div>
                    <div class="flex items-center gap-2 rounded-xl border border-emerald-200/60 bg-white/60 px-3 py-2 dark:border-emerald-700/70 dark:bg-emerald-900/40"><span class="text-xs font-semibold uppercase tracking-widest text-emerald-500 dark:text-emerald-300">বিষয়</span><span class="font-medium text-emerald-700 dark:text-emerald-100">{{ $summary['subject'] }}</span></div>
                    <div class="flex items-center gap-2 rounded-xl border border-emerald-200/60 bg-white/60 px-3 py-2 dark:border-emerald-700/70 dark:bg-emerald-900/40"><span class="text-xs font-semibold uppercase tracking-widest text-emerald-500 dark:text-emerald-300">সাব-বিষয়</span><span class="font-medium text-emerald-700 dark:text-emerald-100">{{ $summary['sub_subject'] }}</span></div>
                    <div class="flex items-center gap-2 rounded-xl border border-emerald-200/60 bg-white/60 px-3 py-2 dark:border-emerald-700/70 dark:bg-emerald-900/40"><span class="text-xs font-semibold uppercase tracking-widest text-emerald-500 dark:text-emerald-300">অধ্যায়</span><span class="font-medium text-emerald-700 dark:text-emerald-100">{{ $summary['chapter'] }}</span></div>
                    <div class="flex items-center gap-2 rounded-xl border border-emerald-200/60 bg-white/60 px-3 py-2 dark:border-emerald-700/70 dark:bg-emerald-900/40"><span class="text-xs font-semibold uppercase tracking-widest text-emerald-500 dark:text-emerald-300">ধরণ</span><span class="font-medium text-emerald-700 dark:text-emerald-100">{{ $summary['type'] }}</span></div>
                    <div class="flex items-center gap-2 rounded-xl border border-emerald-200/60 bg-white/60 px-3 py-2 dark:border-emerald-700/70 dark:bg-emerald-900/40"><span class="text-xs font-semibold uppercase tracking-widest text-emerald-500 dark:text-emerald-300">মোট প্রশ্ন</span><span class="font-medium text-emerald-700 dark:text-emerald-100">{{ $summary['total_questions'] }}</span></div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
                <div class="flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900">
                    <div class="border-b border-slate-100 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-800/60">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">প্রশ্নপত্র প্রিভিউ</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">নির্বাচিত সেটিং অনুসারে লাইভ প্রিভিউ দেখুন।</p>
                    </div>

                    <div class="px-4 pb-6 pt-6 sm:px-6">
                        <div id="question-paper-preview" class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-8 text-slate-800 shadow-lg ring-1 ring-emerald-500/5 dark:border-slate-700 dark:bg-white" data-paper-size="{{ $paperSizeKey }}" data-file-name="{{ Str::slug($summary['exam_name'] ?: 'question-paper') }}" style="font-family: {{ $fontStack }}; font-size: {{ $fontSize }}px;">
                            <div class="flex flex-col gap-4 border-b border-slate-200 pb-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="flex-1 text-center sm:text-left">
                                    <h1 class="text-2xl font-bold tracking-wide text-slate-900">{{ $summary['program_name'] ?? $summary['exam_name'] }}</h1>
                                    @if(! empty($summary['class_level']))
                                        <p class="mt-1 text-lg font-semibold text-slate-700">{{ $summary['class_level'] }}</p>
                                    @endif
                                    <div class="mt-2 space-y-1 text-sm font-medium text-slate-700">
                                        <p>{{ $summary['subject'] }}</p>
                                        @if($previewOptions['showSubSubject'] && ! empty($summary['sub_subject']))
                                            <p>{{ $summary['sub_subject'] }}</p>
                                        @endif
                                        @if($previewOptions['showChapter'] && ! empty($summary['chapter']))
                                            <p>{{ $summary['chapter'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-center gap-3 sm:items-end">
                                    @if($previewOptions['showSetCode'])
                                        <div class="inline-flex overflow-hidden rounded-lg border border-slate-700 text-sm font-semibold text-slate-700">
                                            <span class="bg-slate-100 px-2 py-1 text-xs uppercase tracking-widest text-slate-600">সেট</span>
                                            <span class="px-3 py-1">{{ $summary['set_code'] ?: '—' }}</span>
                                        </div>
                                    @endif
                                    @if($previewOptions['showMarksBox'])
                                        <div class="w-full min-w-[150px] rounded-lg border border-dashed border-teal-500 px-3 py-2 text-center text-sm text-slate-700">
                                            <span class="font-semibold">প্রাপ্ত নম্বর</span>
                                            <span class="mt-1 block h-px w-full bg-teal-500/40"></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm font-medium text-slate-600">
                                <span>সময়— <span class="font-normal text-slate-700">{{ $summary['duration'] ?: '........' }}</span></span>
                                <span>পূর্ণমান— <span class="font-normal text-slate-700">{{ $summary['total_marks'] ?: '........' }}</span></span>
                                <span>প্রশ্ন সংখ্যা— <span class="font-normal text-slate-700">{{ $summary['total_questions'] }}</span></span>
                            </div>

                            @if($previewOptions['showQuestionInfo'])
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">পরীক্ষা: {{ $summary['exam_name'] }}</span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">প্রশ্নের ধরন: {{ $summary['type'] }}</span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">মোট প্রশ্ন: {{ $summary['total_questions'] }}</span>
                                </div>
                            @endif

                            @if($previewOptions['attachAnswerSheet'])
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-500/60 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">উত্তরপত্র সংযুক্ত</span>
                                </div>
                            @endif

                            @if($previewOptions['attachOmrSheet'])
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full border border-cyan-500/60 bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">OMR শীট সংযুক্ত</span>
                                </div>
                            @endif

                            @if($previewOptions['markImportant'])
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">গুরুত্বপূর্ণ প্রশ্ন</span>
                                </div>
                            @endif

                            @if($previewOptions['showInstructions'] && ! empty($summary['instruction_text']))
                                <div class="mt-4 rounded-xl border-l-4 border-amber-400/80 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                    <span class="font-semibold">দ্রষ্টব্যঃ</span>
                                    <span class="ms-1 align-middle">{!! nl2br(e($summary['instruction_text'])) !!}</span>
                                </div>
                            @endif

                            @if($previewOptions['showNotice'] && ! empty($summary['notice_text']))
                                <p class="mt-3 text-center text-sm font-semibold text-slate-700">{!! nl2br(e($summary['notice_text'])) !!}</p>
                            @endif

                            @if($previewOptions['showStudentInfo'])
                                <div class="mt-4 grid gap-1 text-sm text-slate-700">
                                    <p>শিক্ষার্থীর নাম: .......................................................</p>
                                    <p>রোল নং: ............................... শ্রেণি: ...............................</p>
                                    <p>প্রদত্ত নম্বর: ............................................................</p>
                                </div>
                            @endif

                            <div class="mt-6">
                                <ol class="{{ $columnClass }} [column-gap:2.5rem]">
                                    @foreach($summary['questions'] as $index => $question)
                                        <li class="mb-6 break-inside-avoid">
                                            <div class="flex items-start gap-3 text-sm leading-relaxed text-slate-700">
                                                <span class="mt-0.5 font-semibold text-slate-800">{{ $index + 1 }}.</span>
                                                <div class="flex-1 space-y-3">
                                                    <div class="prose prose-sm max-w-none text-slate-800 {{ $textAlignClass }}">{!! $question['title'] !!}</div>
                                                    @if($isMcqPaper && ! empty($question['options']))
                                                        <ul class="grid gap-2 text-slate-700 sm:grid-cols-2">
                                                            @foreach($question['options'] as $optIndex => $option)
                                                                <li class="flex items-start gap-2">
                                                                    <span class="{{ $optionLabelClasses[$optionStyle] ?? $optionLabelClasses['circle'] }}">
                                                                        @php $label = $optionLabels[$optIndex] ?? ($optIndex + 1); @endphp
                                                                        @if($optionStyle === 'parentheses')
                                                                            ({{ $label }})
                                                                        @elseif($optionStyle === 'minimal')
                                                                            {{ $label }}.
                                                                        @else
                                                                            {{ $label }}
                                                                        @endif
                                                                    </span>
                                                                    <span class="prose prose-sm max-w-none text-slate-700">{!! $option !!}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif

                                                    @if($previewOptions['showChapter'] && ! empty($question['chapter']))
                                                        <span class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">{{ $question['chapter'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-800/60 print:hidden">
                        <span class="text-sm text-slate-500 dark:text-slate-300">বর্তমান পেপার সাইজ: {{ $paperSize }}</span>
                        <button type="button" data-question-pdf-trigger data-file-name="{{ Str::slug($summary['exam_name'] ?: 'question-paper') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v4.125c0 .621-.504 1.125-1.125 1.125h-12.75a1.125 1.125 0 01-1.125-1.125V14.25m3.375-3.375L12 15.375m0 0l4.125-4.5M12 15.375V3.75" />
                            </svg>
                            PDF ডাউনলোড
                        </button>
                    </div>
                </div>

                <div class="space-y-4 print:hidden">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-100">কুইক সেটিংস</h4>
                        <button type="button" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/20 dark:text-emerald-200">+ আরও প্রশ্ন যুক্ত করুন</button>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">প্রশ্নে সংযুক্তি</p>
                        <div class="mt-4 space-y-3">
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>উত্তরপত্র</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.attachAnswerSheet">
                            </label>
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>OMR সংযুক্ত</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.attachOmrSheet">
                            </label>
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>গুরুত্বপূর্ণ প্রশ্ন</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.markImportant">
                            </label>
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>প্রশ্নের তথ্য</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.showQuestionInfo">
                            </label>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">প্রশ্নের মেটাডাটা</p>
                        <div class="mt-4 space-y-3">
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>সাব-বিষয়ের নাম</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.showSubSubject">
                            </label>
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>অধ্যায়ের নাম</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.showChapter">
                            </label>
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>সেট কোড</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.showSetCode">
                            </label>
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>নির্দেশনা</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.showInstructions">
                            </label>
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>বিশেষ ঘোষণা</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.showNotice">
                            </label>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">ডকুমেন্ট কাস্টমাইজেশন</p>
                        <div class="mt-4 space-y-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">টেক্সট এলাইনমেন্ট</p>
                                <div class="mt-2 flex gap-2">
                                    @foreach(['left' => 'L', 'center' => 'C', 'right' => 'R', 'justify' => 'J'] as $align => $label)
                                        <button type="button" wire:click="setTextAlign('{{ $align }}')" @class(['inline-flex h-8 w-8 items-center justify-center rounded-lg border text-xs font-semibold transition', 'border-indigo-500 bg-indigo-50 text-indigo-600 shadow-sm' => $textAlign === $align, 'border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700' => $textAlign !== $align])>{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">পেপার সাইজ</p>
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    @foreach(['A4' => 'A4', 'Letter' => 'Letter', 'Legal' => 'Legal', 'A5' => 'A5'] as $size => $label)
                                        <button type="button" wire:click="setPaperSize('{{ $size }}')" @class(['inline-flex items-center justify-center rounded-lg border px-3 py-1.5 text-sm font-medium transition', 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-sm' => $paperSize === $size, 'border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-800' => $paperSize !== $size])>{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">অপশন স্টাইল</p>
                                <div class="mt-2 grid grid-cols-4 gap-2">
                                    <button type="button" wire:click="setOptionStyle('circle')" @class(['inline-flex items-center justify-center rounded-lg border px-2 py-1 text-lg transition', 'border-cyan-600 bg-cyan-50 text-cyan-600 shadow-sm' => $optionStyle === 'circle', 'border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700' => $optionStyle !== 'circle'])>◎</button>
                                    <button type="button" wire:click="setOptionStyle('dot')" @class(['inline-flex items-center justify-center rounded-lg border px-2 py-1 text-lg transition', 'border-emerald-600 bg-emerald-50 text-emerald-600 shadow-sm' => $optionStyle === 'dot', 'border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700' => $optionStyle !== 'dot'])>•</button>
                                    <button type="button" wire:click="setOptionStyle('parentheses')" @class(['inline-flex items-center justify-center rounded-lg border px-2 py-1 text-base transition', 'border-sky-600 bg-sky-50 text-sky-600 shadow-sm' => $optionStyle === 'parentheses', 'border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700' => $optionStyle !== 'parentheses'])>( )</button>
                                    <button type="button" wire:click="setOptionStyle('minimal')" @class(['inline-flex items-center justify-center rounded-lg border px-2 py-1 text-base transition', 'border-slate-700 bg-slate-100 text-slate-700 shadow-sm' => $optionStyle === 'minimal', 'border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700' => $optionStyle !== 'minimal'])>ক.</button>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">ফন্ট পরিবর্তন</p>
                                <select class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" wire:model.live="fontFamily" wire:change="setFontFamily($event.target.value)">
                                    <option value="Bangla">বাংলা</option>
                                    <option value="SolaimanLipi">সোলাইমান লিপি</option>
                                    <option value="Kalpurush">কালপুরুষ</option>
                                    <option value="roman">Times New Roman</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">ফন্ট সাইজ</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-lg font-semibold text-slate-600 hover:border-slate-300 hover:text-slate-800 dark:border-slate-600 dark:text-slate-200" wire:click="decreaseFontSize">-</button>
                                    <span class="w-10 text-center text-sm font-medium text-slate-700 dark:text-slate-200">{{ $fontSize }}</span>
                                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-lg font-semibold text-slate-600 hover:border-slate-300 hover:text-slate-800 dark:border-slate-600 dark:text-slate-200" wire:click="increaseFontSize">+</button>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">কলাম</p>
                                <div class="mt-2 flex gap-2">
                                    @foreach([1, 2, 3] as $col)
                                        <button type="button" wire:click="setColumnCount({{ $col }})" @class(['inline-flex items-center justify-center rounded-lg border px-3 py-1.5 text-sm font-medium transition', 'border-indigo-500 bg-indigo-50 text-indigo-600 shadow-sm' => $columnCount === $col, 'border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-800' => $columnCount !== $col])>{{ $col }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">অতিরিক্ত সেকশন</p>
                        <div class="mt-4 space-y-3">
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>শিক্ষার্থীর তথ্য</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.showStudentInfo">
                            </label>
                            <label class="flex items-center justify-between text-sm font-medium text-slate-600 dark:text-slate-300">
                                <span>প্রাপ্ত নম্বর ঘর</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" wire:model.live="previewOptions.showMarksBox">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-YcsIPJdX0K0qzxYxvt/XM4Jt9V7H5PHeTtNKgHdwNwp0UrEGouGZWlznImPi0tLxe3LjVf6P0M0MZ8WpS0QG2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('click', async (event) => {
            const trigger = event.target.closest('[data-question-pdf-trigger]');
            if (!trigger) {
                return;
            }

            const preview = document.getElementById('question-paper-preview');
            if (!preview) {
                return;
            }

            const format = (preview.dataset.paperSize || 'a4').toLowerCase();
            const fileName = `${trigger.dataset.fileName || preview.dataset.fileName || 'question-paper'}.pdf`;
            const marginMap = { a4: 0.5, letter: 0.5, legal: 0.5, a5: 0.35 };
            const margin = marginMap[format] ?? 0.5;

            trigger.disabled = true;
            trigger.classList.add('opacity-70', 'pointer-events-none');

            try {
                await html2pdf().set({
                    margin,
                    filename: fileName,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
                    jsPDF: { unit: 'in', format, orientation: 'portrait' },
                }).from(preview).save();
            } catch (error) {
                console.error('PDF generation failed', error);
            } finally {
                trigger.disabled = false;
                trigger.classList.remove('opacity-70', 'pointer-events-none');
            }
        });
    </script>
@endpush
