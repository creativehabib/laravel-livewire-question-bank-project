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
            $isMcqPaper = ($questionPaperSummary['type_key'] ?? null) === 'mcq';
            $optionLabels = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ'];
            $summary = $questionPaperSummary;
            $fontClassMap = [
                'Bangla' => 'qp-font-bangla',
                'SolaimanLipi' => 'qp-font-solaiman',
                'Kalpurush' => 'qp-font-kalpurush',
                'roman' => 'qp-font-roman',
            ];
            $fontClass = $fontClassMap[$fontFamily] ?? 'qp-font-bangla';
            $textAlignClass = 'qp-text-' . $textAlign;
        @endphp

        <div class="space-y-6">
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-700 dark:text-gray-200 no-print">
                    <div class="qp-summary-chip"><span class="font-medium">পরীক্ষা:</span> {{ $summary['exam_name'] }}</div>
                    <div class="qp-summary-chip"><span class="font-medium">বিষয়:</span> {{ $summary['subject'] }}</div>
                    <div class="qp-summary-chip"><span class="font-medium">সাব-বিষয়:</span> {{ $summary['sub_subject'] }}</div>
                    <div class="qp-summary-chip"><span class="font-medium">অধ্যায়:</span> {{ $summary['chapter'] }}</div>
                    <div class="qp-summary-chip"><span class="font-medium">প্রশ্নের টাইপ:</span> {{ $summary['type'] }}</div>
                    <div class="qp-summary-chip"><span class="font-medium">মোট প্রশ্ন:</span> {{ $summary['total_questions'] }}</div>
                </div>
            </div>

            <div class="qp-designer-layout">
                <div class="qp-preview-wrapper">
                    <div class="qp-preview-surface">
                        <div class="qp-paper {{ $fontClass }}" data-paper-size="{{ $paperSize }}" style="--qp-font-size: {{ $fontSize }}px; --qp-column-count: {{ $columnCount }};">
                            <div class="qp-paper-header">
                                <div class="qp-paper-header-main">
                                    <h1 class="qp-paper-title">{{ $summary['program_name'] ?? $summary['exam_name'] }}</h1>
                                    @if(! empty($summary['class_level']))
                                        <p class="qp-paper-subtitle">{{ $summary['class_level'] }}</p>
                                    @endif
                                    <p class="qp-paper-subject">{{ $summary['subject'] }}</p>
                                    @if($previewOptions['showSubSubject'] && ! empty($summary['sub_subject']))
                                        <p class="qp-paper-subject">{{ $summary['sub_subject'] }}</p>
                                    @endif
                                    @if($previewOptions['showChapter'] && ! empty($summary['chapter']))
                                        <p class="qp-paper-chapter">{{ $summary['chapter'] }}</p>
                                    @endif
                                </div>
                                <div class="qp-paper-header-side">
                                    @if($previewOptions['showSetCode'])
                                        <div class="qp-setcode-box">
                                            <span class="qp-setcode-label">সেট -</span>
                                            <span class="qp-setcode-value">{{ $summary['set_code'] ?: '—' }}</span>
                                        </div>
                                    @endif
                                    @if($previewOptions['showMarksBox'])
                                        <div class="qp-marks-box">
                                            <span>প্রাপ্ত নম্বর</span>
                                            <span class="qp-marks-line"></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="qp-paper-meta">
                                <div>সময়— <span>{{ $summary['duration'] ?: '........' }}</span></div>
                                <div>পূর্ণমান— <span>{{ $summary['total_marks'] ?: '........' }}</span></div>
                                <div>প্রশ্ন সংখ্যা— <span>{{ $summary['total_questions'] }}</span></div>
                            </div>

                            @if($previewOptions['showQuestionInfo'])
                                <div class="qp-paper-tags">
                                    <span class="qp-badge">পরীক্ষা: {{ $summary['exam_name'] }}</span>
                                    <span class="qp-badge">প্রশ্নের ধরন: {{ $summary['type'] }}</span>
                                    <span class="qp-badge">মোট প্রশ্ন: {{ $summary['total_questions'] }}</span>
                                </div>
                            @endif

                            @if($previewOptions['attachAnswerSheet'])
                                <div class="qp-paper-tags">
                                    <span class="qp-badge qp-badge-outline">উত্তরপত্র সংযুক্ত</span>
                                </div>
                            @endif

                            @if($previewOptions['attachOmrSheet'])
                                <div class="qp-paper-tags">
                                    <span class="qp-badge qp-badge-outline">OMR শীট সংযুক্ত</span>
                                </div>
                            @endif

                            @if($previewOptions['markImportant'])
                                <div class="qp-paper-tags">
                                    <span class="qp-badge qp-badge-important">গুরুত্বপূর্ণ প্রশ্ন</span>
                                </div>
                            @endif

                            @if($previewOptions['showInstructions'] && ! empty($summary['instruction_text']))
                                <div class="qp-paper-instruction">
                                    <span class="qp-paper-instruction-label">দ্রষ্টব্যঃ</span>
                                    <span>{!! nl2br(e($summary['instruction_text'])) !!}</span>
                                </div>
                            @endif

                            @if($previewOptions['showNotice'] && ! empty($summary['notice_text']))
                                <div class="qp-paper-notice">{!! nl2br(e($summary['notice_text'])) !!}</div>
                            @endif

                            @if($previewOptions['showStudentInfo'])
                                <div class="qp-student-info">
                                    <div>শিক্ষার্থীর নাম: .......................................................</div>
                                    <div>রোল নং: ............................... শ্রেণি: ...............................</div>
                                    <div>প্রদত্ত নম্বর: ............................................................</div>
                                </div>
                            @endif

                            <div class="qp-question-area">
                                <ol class="qp-question-list">
                                    @foreach($summary['questions'] as $index => $question)
                                        <li class="qp-question-item">
                                            <div class="qp-question-number">{{ $index + 1 }}.</div>
                                            <div class="qp-question-body">
                                                <div class="qp-question-text {{ $textAlignClass }}">{!! $question['title'] !!}</div>

                                                @if($isMcqPaper && ! empty($question['options']))
                                                    <ul class="qp-option-list qp-option-list--{{ $optionStyle }}">
                                                        @foreach($question['options'] as $optIndex => $option)
                                                            <li class="qp-option-item">
                                                                <span class="qp-option-label qp-option-label--{{ $optionStyle }}">{{ $optionLabels[$optIndex] ?? ($optIndex + 1) }}</span>
                                                                <span class="qp-option-text">{!! $option !!}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                @if($previewOptions['showChapter'] && ! empty($question['chapter']))
                                                    <span class="qp-question-chip">{{ $question['chapter'] }}</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="qp-settings-panel no-print">
                    <div class="qp-settings-card">
                        <h4 class="qp-settings-title">কুইক সেটিংস</h4>
                        <button type="button" class="qp-primary-btn">+ আরও প্রশ্ন যুক্ত করুন</button>
                    </div>

                    <div class="qp-settings-card">
                        <p class="qp-settings-section">প্রশ্নে সংযুক্তি</p>
                        <div class="space-y-2">
                            <div class="qp-toggle-row">
                                <span>উত্তরপত্র</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.attachAnswerSheet">
                                    <span></span>
                                </label>
                            </div>
                            <div class="qp-toggle-row">
                                <span>OMR সংযুক্ত</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.attachOmrSheet">
                                    <span></span>
                                </label>
                            </div>
                            <div class="qp-toggle-row">
                                <span>গুরুত্বপূর্ণ প্রশ্ন</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.markImportant">
                                    <span></span>
                                </label>
                            </div>
                            <div class="qp-toggle-row">
                                <span>প্রশ্নের তথ্য</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.showQuestionInfo">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="qp-settings-card">
                        <p class="qp-settings-section">প্রশ্নের মেটাডাটা</p>
                        <div class="space-y-2">
                            <div class="qp-toggle-row">
                                <span>সাব-বিষয়ের নাম</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.showSubSubject">
                                    <span></span>
                                </label>
                            </div>
                            <div class="qp-toggle-row">
                                <span>অধ্যায়ের নাম</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.showChapter">
                                    <span></span>
                                </label>
                            </div>
                            <div class="qp-toggle-row">
                                <span>সেট কোড</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.showSetCode">
                                    <span></span>
                                </label>
                            </div>
                            <div class="qp-toggle-row">
                                <span>নির্দেশনা</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.showInstructions">
                                    <span></span>
                                </label>
                            </div>
                            <div class="qp-toggle-row">
                                <span>বিশেষ ঘোষণা</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.showNotice">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="qp-settings-card">
                        <p class="qp-settings-section">ডকুমেন্ট কাস্টমাইজেশন</p>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">টেক্সট এলাইনমেন্ট</p>
                                <div class="flex gap-2">
                                    @foreach(['left' => 'L', 'center' => 'C', 'right' => 'R', 'justify' => 'J'] as $align => $label)
                                        <button type="button" wire:click="setTextAlign('{{ $align }}')"
                                                @class(['qp-icon-btn', 'qp-icon-btn--active' => $textAlign === $align])>{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">পেপার সাইজ</p>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(['A4' => 'A4', 'Letter' => 'Letter', 'Legal' => 'Legal', 'A5' => 'A5'] as $size => $label)
                                        <button type="button" wire:click="setPaperSize('{{ $size }}')"
                                                @class(['qp-size-btn', 'qp-size-btn--active' => $paperSize === $size])>{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">অপশন স্টাইল</p>
                                <div class="grid grid-cols-4 gap-2">
                                    <button type="button" wire:click="setOptionStyle('circle')"
                                            @class(['qp-style-btn', 'qp-style-btn--active' => $optionStyle === 'circle'])>◎</button>
                                    <button type="button" wire:click="setOptionStyle('dot')"
                                            @class(['qp-style-btn', 'qp-style-btn--active' => $optionStyle === 'dot'])>•</button>
                                    <button type="button" wire:click="setOptionStyle('parentheses')"
                                            @class(['qp-style-btn', 'qp-style-btn--active' => $optionStyle === 'parentheses'])>( )</button>
                                    <button type="button" wire:click="setOptionStyle('minimal')"
                                            @class(['qp-style-btn', 'qp-style-btn--active' => $optionStyle === 'minimal'])>ক.</button>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">ফন্ট পরিবর্তন</p>
                                <select class="qp-select" wire:model.live="fontFamily" wire:change="setFontFamily($event.target.value)">
                                    <option value="Bangla">বাংলা</option>
                                    <option value="SolaimanLipi">সোলাইমান লিপি</option>
                                    <option value="Kalpurush">কালপুরুষ</option>
                                    <option value="roman">Times New Roman</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">ফন্ট সাইজ</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="qp-icon-btn" wire:click="decreaseFontSize">-</button>
                                    <span class="text-sm font-medium w-10 text-center">{{ $fontSize }}</span>
                                    <button type="button" class="qp-icon-btn" wire:click="increaseFontSize">+</button>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">কলাম</p>
                                <div class="flex gap-2">
                                    @foreach([1, 2, 3] as $col)
                                        <button type="button" wire:click="setColumnCount({{ $col }})"
                                                @class(['qp-size-btn', 'qp-size-btn--active' => $columnCount === $col])>{{ $col }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="qp-settings-card">
                        <p class="qp-settings-section">অতিরিক্ত সেকশন</p>
                        <div class="space-y-2">
                            <div class="qp-toggle-row">
                                <span>শিক্ষার্থীর তথ্য</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.showStudentInfo">
                                    <span></span>
                                </label>
                            </div>
                            <div class="qp-toggle-row">
                                <span>প্রাপ্ত নম্বর ঘর</span>
                                <label class="qp-toggle">
                                    <input type="checkbox" wire:model.live="previewOptions.showMarksBox">
                                    <span></span>
                                </label>
                            </div>
                        </div>
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
        .qp-summary-chip {
            background-color: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 9999px;
            padding: 0.35rem 0.75rem;
        }

        .qp-designer-layout {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .qp-designer-layout {
                grid-template-columns: minmax(0, 1fr) 18rem;
            }
        }

        .qp-preview-wrapper {
            background: linear-gradient(135deg, rgba(236, 253, 245, 0.8), rgba(240, 253, 244, 0.8));
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .qp-preview-surface {
            background: linear-gradient(145deg, rgba(248, 113, 113, 0.12), rgba(16, 185, 129, 0.12));
            border-radius: 14px;
            padding: 1.25rem;
        }

        .qp-paper {
            position: relative;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.25);
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
            color: #1f2937;
            line-height: 1.6;
            font-size: var(--qp-font-size, 14px);
        }

        .qp-font-bangla {
            font-family: 'Noto Sans Bengali', 'SolaimanLipi', sans-serif;
        }

        .qp-font-solaiman {
            font-family: 'SolaimanLipi', 'Noto Sans Bengali', sans-serif;
        }

        .qp-font-kalpurush {
            font-family: 'Kalpurush', 'Noto Sans Bengali', sans-serif;
        }

        .qp-font-roman {
            font-family: 'Times New Roman', serif;
        }

        .qp-paper-header {
            display: flex;
            justify-content: space-between;
            gap: 1.5rem;
            border-bottom: 1px solid rgba(15, 118, 110, 0.2);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .qp-paper-header-main {
            text-align: center;
            flex: 1;
        }

        .qp-paper-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .qp-paper-subtitle {
            font-size: 1.125rem;
            font-weight: 600;
            margin-top: 0.25rem;
        }

        .qp-paper-subject {
            font-weight: 600;
        }

        .qp-paper-chapter {
            font-size: 0.95rem;
            font-weight: 500;
        }

        .qp-paper-header-side {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.75rem;
        }

        .qp-setcode-box {
            display: inline-flex;
            border: 1px solid #1f2937;
            border-radius: 0.375rem;
            overflow: hidden;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .qp-setcode-label {
            padding: 0.25rem 0.5rem;
            border-right: 1px solid #1f2937;
        }

        .qp-setcode-value {
            padding: 0.25rem 0.75rem;
        }

        .qp-marks-box {
            border: 1px dashed rgba(15, 118, 110, 0.5);
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            text-align: center;
            min-width: 140px;
        }

        .qp-marks-line {
            display: block;
            border-bottom: 1px solid rgba(15, 118, 110, 0.5);
            margin-top: 0.25rem;
        }

        .qp-paper-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem 2rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }

        .qp-paper-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .qp-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.75rem;
            background-color: rgba(16, 185, 129, 0.12);
            color: #047857;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .qp-badge-outline {
            background-color: transparent;
            border: 1px dashed rgba(4, 120, 87, 0.5);
        }

        .qp-badge-important {
            background: rgba(239, 68, 68, 0.15);
            color: #b91c1c;
        }

        .qp-paper-instruction {
            background: rgba(252, 211, 77, 0.2);
            border-left: 4px solid rgba(217, 119, 6, 0.6);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .qp-paper-instruction-label {
            font-weight: 700;
            margin-right: 0.5rem;
        }

        .qp-paper-notice {
            text-align: center;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .qp-student-info {
            display: grid;
            gap: 0.35rem;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .qp-question-area {
            margin-top: 1rem;
        }

        .qp-question-list {
            list-style: none;
            margin: 0;
            padding: 0;
            column-count: var(--qp-column-count, 2);
            column-gap: 2.25rem;
        }

        .qp-question-item {
            break-inside: avoid-column;
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .qp-question-number {
            font-weight: 700;
            min-width: 1.5rem;
        }

        .qp-question-body {
            flex: 1;
        }

        .qp-question-text {
            font-size: 1em;
        }

        .qp-text-left {
            text-align: left;
        }

        .qp-text-center {
            text-align: center;
        }

        .qp-text-right {
            text-align: right;
        }

        .qp-text-justify {
            text-align: justify;
        }

        .qp-option-list {
            margin: 0.75rem 0;
            padding: 0;
            list-style: none;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem 1.25rem;
        }

        .qp-option-item {
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
        }

        .qp-option-label {
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.6rem;
        }

        .qp-option-label--circle {
            border: 1.5px solid rgba(14, 116, 144, 0.8);
            border-radius: 9999px;
            height: 1.6rem;
        }

        .qp-option-label--dot {
            border-radius: 9999px;
            height: 1.6rem;
            background-color: rgba(16, 185, 129, 0.15);
        }

        .qp-option-label--parentheses::before {
            content: '(';
            margin-right: 0.15rem;
        }

        .qp-option-label--parentheses::after {
            content: ')';
            margin-left: 0.15rem;
        }

        .qp-option-label--minimal {
            font-weight: 600;
        }

        .qp-question-chip {
            display: inline-flex;
            margin-top: 0.5rem;
            padding: 0.15rem 0.6rem;
            border-radius: 9999px;
            background: rgba(59, 130, 246, 0.12);
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .qp-settings-panel {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .qp-settings-card {
            background: #ffffff;
            border: 1px solid rgba(209, 213, 219, 0.8);
            border-radius: 0.75rem;
            padding: 1rem;
            box-shadow: 0 10px 25px -15px rgba(15, 118, 110, 0.3);
        }

        .qp-settings-title {
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 0.75rem;
        }

        .qp-settings-section {
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .qp-primary-btn {
            width: 100%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            background: #059669;
            color: #fff;
            padding: 0.6rem 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .qp-primary-btn:hover {
            background: #047857;
        }

        .qp-toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .qp-toggle {
            position: relative;
            display: inline-flex;
            width: 44px;
            height: 24px;
        }

        .qp-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .qp-toggle span {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #d1d5db;
            border-radius: 9999px;
            transition: all 0.2s ease;
        }

        .qp-toggle span::after {
            content: '';
            position: absolute;
            height: 18px;
            width: 18px;
            left: 3px;
            top: 3px;
            background-color: #ffffff;
            border-radius: 50%;
            transition: transform 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .qp-toggle input:checked + span {
            background-color: #10b981;
        }

        .qp-toggle input:checked + span::after {
            transform: translateX(20px);
        }

        .qp-icon-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            border: 1px solid rgba(209, 213, 219, 0.8);
            background: #ffffff;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .qp-icon-btn--active,
        .qp-icon-btn:hover {
            background: #10b981;
            color: #ffffff;
            border-color: #10b981;
        }

        .qp-size-btn,
        .qp-style-btn {
            padding: 0.5rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(209, 213, 219, 0.9);
            background: #ffffff;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s ease;
        }

        .qp-size-btn--active,
        .qp-style-btn--active,
        .qp-size-btn:hover,
        .qp-style-btn:hover {
            border-color: #10b981;
            color: #047857;
            background: rgba(16, 185, 129, 0.1);
        }

        .qp-select {
            width: 100%;
            border: 1px solid rgba(209, 213, 219, 0.9);
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
            background: #ffffff;
        }

        @media (max-width: 640px) {
            .qp-option-list {
                grid-template-columns: 1fr;
            }

            .qp-paper-header {
                flex-direction: column;
                align-items: center;
            }

            .qp-paper-header-side {
                align-items: center;
            }
        }

        @media print {
            body {
                background: #ffffff !important;
            }

            .no-print {
                display: none !important;
            }

            .qp-preview-wrapper,
            .qp-preview-surface,
            .qp-paper {
                box-shadow: none !important;
                border: none !important;
                background: #ffffff;
            }

            .qp-question-list {
                column-gap: 18mm;
            }

            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
@endpush
