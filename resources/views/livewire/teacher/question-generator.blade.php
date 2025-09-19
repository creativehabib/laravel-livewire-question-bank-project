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


            <div class="qp-designer-layout">
                <div class="table-bordered py-4 print:p-0 print:overflow-hidden bg-gray-100 min-h-[95vh] print:bg-white">
                    <div class="{{ $fontClass }} flex flex-col lg:flex-row justify-center  gap-5  print:gap-0 mx-4 print:mx-0">
                        <div class="print:hidden  flex gap-x-2 justify-between sticky top-12 lg:hidden  p-2 text-center z-10 bg-white">
                            <button class="flex justify-center gap-1 items-center border py-1 px-2 bg-white rounded" tabindex="0">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-gray-600 text-xs" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"></path>
                                </svg>
                                <span>সেটিংস</span>
                            </button>
                            <button class="flex justify-center gap-1 items-center border py-1 px-2 bg-white rounded" tabindex="0">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-gray-600 text-xs" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                                </svg>
                                <span>উত্তরমালা</span>
                            </button>
                            <button class="flex justify-center gap-1 items-center border py-1 px-2 bg-white rounded" tabindex="0">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-gray-600 text-xs" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"></path>
                                </svg>
                                <span>ডাউনলোড</span>
                            </button>
                        </div>
                        <div class="hidden fixed print:hidden lg:hidden left-0 top-0 z-[100] h-full bg-gray-900/50 w-screen -ml-20"></div>
                        <div class="print-area relative min-w-screen md:overflow-auto lg:w-[210mm] {{ $fontClass }}">
                            <div class="bg-white mb-3 print:hidden border-t-2 border-emerald-500">
                                <p class="text-center font-bold bg-emerald-50 p-1">কুইক সেটিংস</p>
                                <div class=" p-2">
                                    <button class="bg-emerald-600 hover:opacity-90 px-2 py-1 text-white ">+ আরও প্রশ্ন যুক্ত করুন</button>
                                </div>
                            </div>
                            <div class="w-full p-[5mm] md:p-[10mm] print:p-0.5 print:w-full print:shadow-none bg-white">
                                <div class="relative py-2 print:py-0">
                                    <h1 class="text-xl font-bold text-center">{{ $summary['program_name'] ?? $summary['exam_name'] }}</h1>
                                    <div class="relative">
                                        <p contenteditable="true" class="text-center text-lg editable-effect">{{ $summary['subject'] }}</p>
                                        @if($previewOptions['showSubSubject'] && ! empty($summary['sub_subject']))
                                        <p contenteditable="true" class="text-center editable-effect">{{ $summary['sub_subject'] }}</p>
                                        @endif
                                        @if($previewOptions['showChapter'] && ! empty($summary['chapter']))
                                        <p contenteditable="true" class="text-center editable-effect">{{ $summary['chapter'] }}</p>
                                        @endif
                                        <div class="absolute -top-1 right-0 flex">
                                            <p class="border-y border-l pl-1 border-black editable-effect" contenteditable="true">সেট -</p>
                                            <p contenteditable="true" class="border-y border-r px-1 border-black font-bold editable-effect">গ</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-between relative b">
                                        <div class="flex items-center editable-effect" contenteditable="true">সময়— <span class="mx-1">৩০ মিনিট</span></div>
                                        <div contenteditable="true" class="editable-effect">পূর্ণমান— <span class="mx-1">২০</span></div>
                                    </div>
                                    <hr>
                                    <div class="text-center text-sm my-1 editable-effect" contenteditable="true">
                                        <span>
                                          <i>
                                            <span class="bangla-bold">দ্রষ্টব্যঃ</span> সরবরাহকৃত নৈর্ব্যত্তিক অভীক্ষার উত্তরপত্রে প্রশ্নের ক্রমিক নম্বরের বিপরীতে প্রদত্ত বর্ণসম্বলিত বৃত্ত সমুহ হতে সঠিক উত্তরের বৃত্তটি </i> ( <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="inline-block" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"></path>
                                          </svg>) <i>বল পয়েন্ট কলম দ্বারা সম্পুর্ণ ভরাট করো । প্রতিটি প্রশ্নের মান ১ ।</i>
                                        </span>
                                    </div>
                                    <div contenteditable="true" class="text-center text-sm mt-1 font-bold editable-effect">প্রশ্নপত্রে কোনো প্রকার দাগ/চিহ্ন দেয়া যাবেনা ।</div>
                                </div>
                                <div style="font-size: 14px;">
                                    <div style="text-align: justify;">
                                        <div class="relative flex-1 columns-1 lg:columns-2 print:columns-2" style="column-rule: 1px solid rgba(0, 0, 0, 0.2);">
                                            @foreach($summary['questions'] as $index => $question)
                                                <div class="false bg-white relative p-0.5 hover:bg-gray-50 rounded group">
                                                <div class=" flex items-baseline gap-x-2 ">
                                                    <span contenteditable="true" class="editable-effect">{{ $index + 1 }}.</span>
                                                    <div class="flex flex-wrap justify-between items-center w-full">
                                                        <div contenteditable="false" class="false bangla-bold">{!! $question['title'] !!}</div>
                                                    </div>
                                                </div>
                                                @if($isMcqPaper && ! empty($question['options']))
                                                    <div class="relative grid grid-cols-2 ml-7 group">
                                                        @foreach($question['options'] as $optIndex => $option)
                                                            <div class="option flex flex-1 items-baseline mb-0.5">
                                                                <div class="h-4 w-4 border border-gray-500 shrink-0 mr-1 rounded-full flex justify-center items-center">{{ $optionLabels[$optIndex] ?? ($optIndex + 1) }} <span></span><span></span>
                                                                </div>
                                                                <div contenteditable="false" class="false">{!! $option !!}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden select-none fixed top-0 right-0 z-[1000] overflow-y-auto lg:z-0 bg-white lg:bg-none lg:sticky lg:block lg:top-16 h-screen w-72 google-shadow print:hidden sidebar">
                            <div class="relative overflow-hidden">
                                <div class=" bg-white backdrop-blur-lg p-2">
                                    <h1 class="py-2 flex items-center gap-2 justify-center rounded text-center bg-gray-50 mb-2 shadow">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-gray-500 text-sm" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"></path>
                                        </svg>
                                        <span class="text-lg">সেটিংস</span>
                                    </h1>
                                    <button class="hover:bg-primary-400 bg-primary-500 transition-all py-2 rounded w-full text-center text-white flex items-center gap-2 justify-center">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"></path>
                                        </svg>ডাউনলোড </button>
                                    <div class="my-4 ">
                                        <p class="bg-emerald-50 p-2 font-bold border-t border-emerald-500">প্রশ্নে সংযুক্তি</p>
                                        <div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">উত্তরপত্র</span>
                                                <label class="relative inline-flex items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" wire:model.live="previewOptions.attachAnswerSheet">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                            <div>
                                                <div class="bg-gray-100 p-2 rounded flex justify-between items-center my-1">
                                                    <span class="bangla">OMR সংযুক্ত</span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer" wire:model.live="previewOptions.markImportant">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">গুরুত্বপূর্ণ প্রশ্ন </span>
                                                <label class="relative inline-flex items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" wire:model.live="previewOptions.markImportant">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                            <div class="bg-gray-100 p-2 rounded flex justify-between items-center my-1">
                                                <span class="bangla">প্রশ্নের তথ্য</span>
                                                <label class="relative inline-flex  items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" wire:model.live="previewOptions.showQuestionInfo">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                            <div class="relative rounded">
                                                <div class="bg-gray-100 p-2 rounded flex justify-between items-center">
                                                    <span class="bangla">শিক্ষার্থীর তথ্য</span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="relative rounded mt-1">
                                                <div class="bg-gray-100 p-2 rounded flex justify-between items-center">
                                                    <span class="bangla"> প্রাপ্ত নম্বর ঘর </span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" my-5">
                                        <p class="bg-emerald-50 p-2 font-bold border-t border-emerald-500">প্রশ্নের মেটাডাটা</p>
                                        <div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">বিষয়ের নাম</span>
                                                <label class="relative inline-flex items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" wire:model.live="previewOptions.showSubSubject">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">অধ্যায়ের নাম</span>
                                                <label class="relative inline-flex items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" checked="" wire:model.live="previewOptions.showChapter">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">সেট কোড </span>
                                                <label class="relative inline-flex items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" checked="" wire:model.live="previewOptions.showSetCode">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">প্রোগ্রাম/পরিক্ষার নাম</span>
                                                <label class="relative inline-flex items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" value="">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">নির্দেশনা</span>
                                                <label class="relative inline-flex items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" checked="" wire:model.live="previewOptions.showInstructions">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" my-5">
                                        <p class="bg-emerald-50 p-2 font-bold border-t border-emerald-500">ডকুমেন্ট কাস্টমাইজেসন</p>
                                        <div>
                                            <div class="bg-gray-100 p-2 rounded  my-1">
                                                <div class=" flex justify-between items-center">
                                                    <span class="bangla"> এডিটিং মোড </span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="bg-gray-100 p-1">
                                                <div class="flex justify-between gap-2">
                                                    <p>টেক্সট এলাইনমেন্ট</p>
                                                </div>
                                                <div class="flex gap-2 py-1">
                                                    <button class="p-2 rounded bg-white text-gray-700" title="left">
                                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12.83 352h262.34A12.82 12.82 0 0 0 288 339.17v-38.34A12.82 12.82 0 0 0 275.17 288H12.83A12.82 12.82 0 0 0 0 300.83v38.34A12.82 12.82 0 0 0 12.83 352zm0-256h262.34A12.82 12.82 0 0 0 288 83.17V44.83A12.82 12.82 0 0 0 275.17 32H12.83A12.82 12.82 0 0 0 0 44.83v38.34A12.82 12.82 0 0 0 12.83 96zM432 160H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0 256H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"></path>
                                                        </svg>
                                                    </button>
                                                    <button class="p-2 rounded bg-white text-gray-700" title="center">
                                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M352 64c0-17.7-14.3-32-32-32H128c-17.7 0-32 14.3-32 32s14.3 32 32 32H320c17.7 0 32-14.3 32-32zm96 128c0-17.7-14.3-32-32-32H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H416c17.7 0 32-14.3 32-32zM0 448c0 17.7 14.3 32 32 32H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H32c-17.7 0-32 14.3-32 32zM352 320c0-17.7-14.3-32-32-32H128c-17.7 0-32 14.3-32 32s14.3 32 32 32H320c17.7 0 32-14.3 32-32z"></path>
                                                        </svg>
                                                    </button>
                                                    <button class="p-2 rounded bg-white text-gray-700" title="right">
                                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M16 224h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16zm416 192H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm3.17-384H172.83A12.82 12.82 0 0 0 160 44.83v38.34A12.82 12.82 0 0 0 172.83 96h262.34A12.82 12.82 0 0 0 448 83.17V44.83A12.82 12.82 0 0 0 435.17 32zm0 256H172.83A12.82 12.82 0 0 0 160 300.83v38.34A12.82 12.82 0 0 0 172.83 352h262.34A12.82 12.82 0 0 0 448 339.17v-38.34A12.82 12.82 0 0 0 435.17 288z"></path>
                                                        </svg>
                                                    </button>
                                                    <button class="p-2 rounded bg-emerald-600 text-white" title="justify">
                                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M432 416H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-128H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-128H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-128H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="my-1">
                                                <p> পেপার সাইজ </p>
                                                <div class="grid grid-cols-2 gap-1.5">
                                                    <div class="flex-1 flex flex-col items-center justify-center cursor-pointer px-2 py-2 border rounded text-sm capitalize select-none bg-emerald-50 border-emerald-500">
                                                        <div class="mb-1 border shadow bg-white" style="min-width: 42.4242px; min-height: 60px;"></div>A4
                                                    </div>
                                                    <div class="flex-1 flex flex-col items-center justify-center cursor-pointer px-2 py-2 border rounded text-sm capitalize select-none border-gray-200 hover:bg-gray-200">
                                                        <div class="mb-1 border shadow bg-white" style="min-width: 45px; min-height: 58.125px;"></div>Letter
                                                    </div>
                                                    <div class="flex-1 flex flex-col items-center justify-center cursor-pointer px-2 py-2 border rounded text-sm capitalize select-none border-gray-200 hover:bg-gray-200">
                                                        <div class="mb-1 border shadow bg-white" style="min-width: 36.4045px; min-height: 60px;"></div>Legal
                                                    </div>
                                                    <div class="flex-1 flex flex-col items-center justify-center cursor-pointer px-2 py-2 border rounded text-sm capitalize select-none border-gray-200 hover:bg-gray-200">
                                                        <div class="mb-1 border shadow bg-white" style="min-width: 42.2857px; min-height: 60px;"></div>A5
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="relative bg-gray-100 p-2 rounded my-1">
                                                <p class="bangla mb-2">অপশন স্টাইল</p>
                                                <div class="flex gap-2">
                                                    <div class="p-1 flex-1 flex justify-center items-center cursor-pointer bg-emerald-600">
                                                        <div class="h-5 w-5 border border-gray-500 rounded-full bg-white"></div>
                                                    </div>
                                                    <div class="p-1 flex-1 flex justify-center items-center cursor-pointer bg-white hover:bg-emerald-100">.</div>
                                                    <div class="p-1 flex-1 flex justify-center items-center cursor-pointer bg-white hover:bg-emerald-100">( )</div>
                                                    <div class="p-1 flex-1 flex justify-center items-center cursor-pointer bg-white hover:bg-emerald-100">)</div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-100 my-1 p-2">
                                                <div class="rounded  justify-between items-center">
                                                    <p class="bangla mb-1 text-center">ফন্ট পরিবর্তন</p>
                                                    <select id="font-selector">
                                                        <option value="Bangla">বাংলা</option>
                                                        <option value="SolaimanLipi">সোলাইমান লিপি</option>
                                                        <option value="Kalpurush">কালপুরুষ</option>
                                                        <option value="roman">Times New Roman</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">ফন্ট সাইজ </span>
                                                <div class="flex items-center gap-1">
                                                    <button class="hover:bg-white px-2 rounded text-lg">-</button>
                                                    <p class="border rounded p-0.5 px-1.5 bg-white">14</p>
                                                    <button class="hover:bg-white px-2 rounded text-lg">+</button>
                                                </div>
                                            </div>
                                            <div class="my-1">
                                                <p>কলাম</p>
                                                <div class="flex gap-2 justify-center">
                                                    <div class="border hover:border-gray-500  rounded py-2 px-3 flex-1 cursor-pointer">
                                                        <div class="flex justify-center gap-0.5 items-center">
                                                            <div class="w-4 h-6 bg-gray-300"></div>
                                                        </div>
                                                    </div>
                                                    <div class="border border-black  rounded py-2 px-3 flex-1 cursor-pointer">
                                                        <div class="flex justify-center gap-0.5 items-center">
                                                            <div class="w-4 h-6 bg-gray-300"></div>
                                                            <div class="w-4 h-6 bg-gray-300"></div>
                                                        </div>
                                                    </div>
                                                    <div class="border hover:border-gray-500  rounded py-2 px-3 flex-1 cursor-pointer">
                                                        <div class="flex justify-center gap-0.5 items-center">
                                                            <div class="w-4 h-6 bg-gray-300"></div>
                                                            <div class="w-4 h-6 bg-gray-300"></div>
                                                            <div class="w-4 h-6 bg-gray-300"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-100 p-2 rounded  flex justify-between items-center my-1">
                                                <span class="bangla">কলাম ডিভাইডার</span>
                                                <label class="relative inline-flex items-center  cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" value="" checked="">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-5">
                                        <p class="bg-emerald-50 p-2 font-bold border-t border-emerald-500">সহায়ক টুলস</p>
                                        <div>
                                            <div class="p-1.5 bg-gray-100 rounded my-1 border-2 border-rose-500 relative">
                                                <div class="flex justify-between items-center">
                  <span class="bangla font-medium flex items-center">পুনরাবৃত্ত প্রশ্ন শনাক্ত <span class="animate-pulse ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">নতুন</span>
                  </span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-emerald-600 relative after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                                                    </label>
                                                </div>
                                                <div class="text-sm bg-white mt-1 text-center">একই প্রশ্ন একাধিকবার নির্বাচিত হলে সহজে শনাক্ত ও পরিবর্তন করা যাবে।</div>
                                            </div>
                                            <div>
                                                <div class="relative bg-gray-100 p-2 rounded flex justify-between items-center my-1">
                                                    <span class="bangla">শীট</span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <div whiletap="[object Object]" class="cursor-pointer bg-gray-100 hover:bg-emerald-600 hover:text-white flex justify-between items-center p-2 rounded my-1">
                                                    <p>শাফল (সেট কোড তৈরী) </p>
                                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M403.8 34.4c12-5 25.7-2.2 34.9 6.9l64 64c6 6 9.4 14.1 9.4 22.6s-3.4 16.6-9.4 22.6l-64 64c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V160H352c-10.1 0-19.6 4.7-25.6 12.8L284 229.3 244 176l31.2-41.6C293.3 110.2 321.8 96 352 96h32V64c0-12.9 7.8-24.6 19.8-29.6zM164 282.7L204 336l-31.2 41.6C154.7 401.8 126.2 416 96 416H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96c10.1 0 19.6-4.7 25.6-12.8L164 282.7zm274.6 188c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V416H352c-30.2 0-58.7-14.2-76.8-38.4L121.6 172.8c-6-8.1-15.5-12.8-25.6-12.8H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96c30.2 0 58.7 14.2 76.8 38.4L326.4 339.2c6 8.1 15.5 12.8 25.6 12.8h32V320c0-12.9 7.8-24.6 19.8-29.6s25.7-2.2 34.9 6.9l64 64c6 6 9.4 14.1 9.4 22.6s-3.4 16.6-9.4 22.6l-64 64z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" my-5">
                                        <p class="bg-emerald-50 p-2 font-bold border-t border-emerald-500">ব্রান্ডিং</p>
                                        <div>
                                            <div>
                                                <div class="bg-gray-100 p-2 rounded flex justify-between items-center my-1">
                                                    <span class="bangla">ঠিকানা</span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="bg-gray-100  my-1  p-2">
                                                <div class="rounded flex justify-between items-center">
                                                    <span class="bangla">জলছাপ</span>
                                                    <label class="relative inline-flex  items-center  cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer" value="">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peerdark:peer-focus:ring-emerald-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="h-40"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')

@endpush

@push('scripts')
@endpush
