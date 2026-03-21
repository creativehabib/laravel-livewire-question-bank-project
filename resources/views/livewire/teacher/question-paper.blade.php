@php
    $fontClassMap = [
        'Bangla' => 'qp-font-bangla',
        'HindSiliguri' => 'qp-font-hind-siliguri',
        'SolaimanLipi' => 'qp-font-solaiman',
        'Kalpurush' => 'qp-font-kalpurush',
        'Shurjo' => 'qp-font-shurjo',
        'roman' => 'qp-font-roman',
    ];

    $fontClass = $fontClassMap[$fontFamily] ?? 'qp-font-bangla';

    $paperSizes = [
        'A4' => ['width' => '210mm', 'min_height' => '297mm', 'preview_width' => '42.42px', 'preview_height' => '60px'],
        'Letter' => ['width' => '216mm', 'min_height' => '279mm', 'preview_width' => '45px', 'preview_height' => '58.13px'],
        'Legal' => ['width' => '216mm', 'min_height' => '356mm', 'preview_width' => '36.4px', 'preview_height' => '60px'],
        'A5' => ['width' => '148mm', 'min_height' => '210mm', 'preview_width' => '42.29px', 'preview_height' => '60px'],
    ];

    $activePaper = $paperSizes[$paperSize] ?? $paperSizes['A4'];

    $textAlignments = [
        'left' => 'বাম',
        'center' => 'মাঝখান',
        'right' => 'ডান',
        'justify' => 'জাস্টিফাই',
    ];

    $optionStyles = [
        'circle' => '○',
        'dot' => '.',
        'bracket' => '( )',
        'suffix' => ')',
    ];

    $optionMarker = static function (string $style, int $index): string {
        $label = mb_chr(2453 + $index);

        return match ($style) {
            'dot' => $label . '.',
            'bracket' => '(' . $label . ')',
            'suffix' => $label . ')',
            default => $label,
        };
    };
@endphp

<div class="bg-gray-100 min-h-[95vh] py-4 print:bg-white print:p-0">
    <div class="mx-4 flex flex-col justify-center gap-5 lg:flex-row print:mx-0 print:gap-0 {{ $fontClass }}">
        <div class="print-area relative md:overflow-auto" style="width: {{ $activePaper['width'] }}; max-width: 100%;">
            <div class="mb-3 border-t-2 border-emerald-500 bg-white print:hidden">
                <p class="bg-emerald-50 p-1 text-center font-bold">কুইক সেটিংস</p>
                <div class="p-2 flex flex-wrap gap-2">
                    <a href="{{ route('questions.view', ['qset' => $questionSet->id]) }}" class="bg-emerald-600 px-3 py-2 text-white hover:opacity-90">+ আরও প্রশ্ন যুক্ত করুন</a>
                    <button type="button" onclick="window.print()" class="rounded bg-slate-700 px-3 py-2 text-white hover:bg-slate-800">প্রিন্ট / ডাউনলোড</button>
                    <button type="button" wire:click="shuffleQuestions" class="rounded bg-amber-500 px-3 py-2 text-white hover:bg-amber-600">শাফল + সেট কোড</button>
                </div>
            </div>

            <div class="relative w-full bg-white p-[5mm] shadow-sm md:p-[10mm] print:w-full print:p-1 print:shadow-none" style="min-height: {{ $activePaper['min_height'] }};">
                @if($previewOptions['showWatermark'])
                    <div class="pointer-events-none absolute inset-0 flex items-center justify-center overflow-hidden">
                        <div class="rotate-[-30deg] whitespace-nowrap font-black uppercase tracking-[0.4em] text-slate-400"
                             style="font-size: {{ $watermarkSize }}px; opacity: {{ $watermarkOpacity / 100 }};">
                            {{ $watermarkText }}
                        </div>
                    </div>
                @endif

                <div class="relative z-10 py-2 print:py-0">
                    <h1 class="text-center text-xl font-bold">{{ $instituteName }}</h1>

                    @if($previewOptions['showExamName'])
                        <p class="text-center text-lg font-bold">{{ $questionSet->name }}</p>
                    @endif

                    <div class="relative">
                        <p class="text-center text-lg">{{ $subject?->name }}</p>

                        @if($previewOptions['showSubSubject'] && filled($subSubject?->name))
                            <p class="text-center">{{ $subSubject?->name }}</p>
                        @endif

                        @if($previewOptions['showChapter'] && $chapters->isNotEmpty())
                            <p class="text-center text-sm">({{ $chapters->pluck('name')->implode(', ') }})</p>
                        @endif

                        @if($previewOptions['showSetCode'])
                            <div class="absolute right-0 top-0 flex text-sm md:text-base">
                                <span class="border-y border-l border-black px-2">সেট -</span>
                                <span class="border-y border-r border-black px-2 font-bold">{{ $setCode }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="relative flex justify-between gap-3">
                        <div>সময় — <span class="mx-1">{{ round($questions->count() * 84 / 60) }} মিনিট</span></div>
                        <div>পূর্ণমান — <span class="mx-1">{{ $questions->sum('marks') }}</span></div>
                    </div>

                    @if($previewOptions['showStudentInfo'])
                        <div class="mt-2 grid grid-cols-2 gap-3 text-sm">
                            <div class="border-b border-dashed border-gray-500 pb-1">শিক্ষার্থীর নাম: </div>
                            <div class="border-b border-dashed border-gray-500 pb-1">রোল: </div>
                        </div>
                    @endif

                    <hr class="my-2">

                    @if($previewOptions['showInstructions'])
                        <div class="my-1 text-center text-sm italic">
                            দ্রষ্টব্যঃ সরবরাহকৃত নৈর্ব্যত্তিক অভীক্ষার উত্তরপত্রে প্রশ্নের ক্রমিক নম্বরের বিপরীতে প্রদত্ত বর্ণসম্বলিত বৃত্তসমূহ হতে সঠিক উত্তরের বৃত্তটি সম্পূর্ণ ভরাট করো।
                        </div>
                    @endif

                    @if($previewOptions['showNotice'])
                        <div class="mt-1 text-center text-sm font-bold">প্রশ্নপত্রে কোনো প্রকার দাগ/চিহ্ন দেয়া যাবে না।</div>
                    @endif
                </div>

                <div class="relative z-10" style="font-size: {{ $fontSize }}px; text-align: {{ $textAlign }};">
                    <div class="gap-5"
                         style="column-count: {{ $columnCount }}; column-gap: 24px; {{ $previewOptions['showColumnDivider'] && $columnCount > 1 ? 'column-rule: 1px solid rgba(0,0,0,.18);' : '' }}">
                        @forelse ($questions as $question)
                            <div class="mb-3 break-inside-avoid rounded bg-white p-1 hover:bg-gray-50" wire:key="question-{{ $question->id }}-{{ $loop->iteration }}">
                                <div class="flex items-baseline justify-between gap-2">
                                    <div class="flex w-full items-baseline gap-2">
                                        <span>{{ $loop->iteration }}.</span>
                                        <div class="w-full">
                                            <div class="font-semibold">{!! $question->title !!}</div>
                                        </div>
                                    </div>

                                    @if($question->question_type !== 'cq' && $question->marks > 0 && $previewOptions['showMarksBox'])
                                        <span class="shrink-0 font-bold">[{{ $question->marks }}]</span>
                                    @endif
                                </div>

                                @php
                                    $mcqOptions = [];
                                    if ($question->question_type === 'mcq') {
                                        if (!empty($question->extra_content)) {
                                            $mcqOptions = is_array($question->extra_content) ? $question->extra_content : (json_decode($question->extra_content, true) ?: []);
                                        } elseif ($question->options && $question->options->isNotEmpty()) {
                                            $mcqOptions = $question->options->toArray();
                                        }
                                    }

                                    $cqParts = [];
                                    if ($question->question_type === 'cq' && !empty($question->extra_content)) {
                                        $cqParts = is_array($question->extra_content) ? $question->extra_content : (json_decode($question->extra_content, true) ?: []);
                                    }
                                @endphp

                                @if($question->question_type === 'mcq' && ! empty($mcqOptions))
                                    <div class="mt-2 ml-6 grid grid-cols-1 gap-x-3 gap-y-1 sm:grid-cols-2">
                                        @foreach ($mcqOptions as $option)
                                            @php
                                                $isCorrect = ! empty($option['is_correct']);
                                            @endphp
                                            <div class="flex items-baseline gap-2">
                                                @if($optionStyle === 'circle')
                                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border text-[11px] {{ $previewOptions['attachAnswerSheet'] && $isCorrect ? 'border-gray-700 bg-gray-700 text-white' : 'border-gray-500' }}">
                                                        {{ mb_chr(2453 + $loop->index) }}
                                                    </div>
                                                @else
                                                    <div class="min-w-[2rem] shrink-0 font-semibold">{{ $optionMarker($optionStyle, $loop->index) }}</div>
                                                @endif
                                                <div>{!! $option['option_text'] ?? '' !!}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if($question->question_type === 'cq' && ! empty($cqParts))
                                    <div class="mt-2 ml-6 space-y-1.5">
                                        @foreach ($cqParts as $part)
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-baseline gap-2">
                                                    <span class="font-bold">{{ $part['label'] ?? '' }}.</span>
                                                    <div>{!! $part['text'] ?? '' !!}</div>
                                                </div>
                                                @if($previewOptions['showMarksBox'])
                                                    <span class="shrink-0 font-bold">{{ $part['marks'] ?? '' }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500">এই প্রশ্নপত্রে এখনো কোনো প্রশ্ন যুক্ত করা হয়নি।</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <aside class="sidebar top-16 h-screen w-full overflow-y-auto bg-white p-3 lg:sticky lg:block lg:w-80 print:hidden">
            <div class="space-y-5">
                <div>
                    <h1 class="mb-2 rounded bg-gray-50 py-2 text-center text-lg shadow">সেটিংস</h1>
                    <button type="button" onclick="window.print()" class="flex w-full items-center justify-center gap-2 rounded bg-primary-500 py-2 text-center text-white transition-all hover:bg-primary-400">ডাউনলোড</button>
                </div>

                <div>
                    <p class="border-t border-emerald-500 bg-emerald-50 p-2 font-bold">প্রশ্নে সংযুক্তি</p>
                    <div class="space-y-2 pt-2">
                        @foreach ([
                            'attachAnswerSheet' => 'উত্তরপত্র',
                            'attachOmrSheet' => 'OMR সংযুক্ত',
                            'markImportant' => 'গুরুত্বপূর্ণ প্রশ্ন',
                            'showMarksBox' => 'প্রশ্নের মান (Marks)',
                            'showStudentInfo' => 'শিক্ষার্থীর তথ্য',
                        ] as $previewKey => $label)
                            <label class="flex items-center justify-between rounded bg-gray-100 p-2">
                                <span>{{ $label }}</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-emerald-600" wire:model.live="previewOptions.{{ $previewKey }}">
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="border-t border-emerald-500 bg-emerald-50 p-2 font-bold">প্রশ্নের মেটাডাটা</p>
                    <div class="space-y-2 pt-2">
                        @foreach ([
                            'showSubSubject' => 'বিষয়ের নাম',
                            'showChapter' => 'অধ্যায়ের নাম',
                            'showSetCode' => 'সেট কোড',
                            'showExamName' => 'প্রোগ্রাম/পরীক্ষার নাম',
                            'showInstructions' => 'নির্দেশনা',
                            'showNotice' => 'নোটিশ',
                        ] as $previewKey => $label)
                            <label class="flex items-center justify-between rounded bg-gray-100 p-2">
                                <span>{{ $label }}</span>
                                <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-emerald-600" wire:model.live="previewOptions.{{ $previewKey }}">
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="border-t border-emerald-500 bg-emerald-50 p-2 font-bold">ডকুমেন্ট কাস্টমাইজেশন</p>
                    <div class="space-y-3 pt-2">
                        <div class="rounded bg-gray-100 p-2">
                            <p class="mb-2 font-medium">টেক্সট এলাইনমেন্ট</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($textAlignments as $alignment => $label)
                                    <button type="button" wire:click="setTextAlign('{{ $alignment }}')"
                                            class="rounded border px-3 py-2 text-sm {{ $textAlign === $alignment ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-gray-300 bg-white text-gray-700' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="mb-2 font-medium">পেপার সাইজ</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($paperSizes as $size => $dimensions)
                                    <button type="button" wire:click="setPaperSize('{{ $size }}')"
                                            class="flex flex-col items-center justify-center rounded border px-2 py-2 text-sm {{ $paperSize === $size ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 bg-white hover:bg-gray-100' }}">
                                        <span class="mb-1 border bg-white shadow-sm" style="min-width: {{ $dimensions['preview_width'] }}; min-height: {{ $dimensions['preview_height'] }};"></span>
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded bg-gray-100 p-2">
                            <p class="mb-2 font-medium">অপশন স্টাইল</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($optionStyles as $style => $preview)
                                    <button type="button" wire:click="setOptionStyle('{{ $style }}')"
                                            class="rounded border px-3 py-2 text-center {{ $optionStyle === $style ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-gray-300 bg-white' }}">
                                        {{ $preview }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded bg-gray-100 p-2">
                            <label for="font-selector" class="mb-2 block text-center font-medium">ফন্ট পরিবর্তন</label>
                            <select id="font-selector" wire:model.live="fontFamily" class="w-full rounded-md border border-gray-300">
                                @foreach($fontOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rounded bg-gray-100 p-2">
                            <div class="mb-2 flex items-center justify-between">
                                <span>ফন্ট সাইজ</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" wire:click="decreaseFontSize" class="rounded bg-white px-2 text-lg">-</button>
                                    <span class="rounded border bg-white px-2 py-0.5">{{ $fontSize }}</span>
                                    <button type="button" wire:click="increaseFontSize" class="rounded bg-white px-2 text-lg">+</button>
                                </div>
                            </div>
                            <input type="range" min="10" max="24" wire:model.live="fontSize" class="w-full">
                        </div>

                        <div>
                            <p class="mb-2 font-medium">কলাম</p>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach([1, 2, 3] as $count)
                                    <button type="button" wire:click="setColumnCount({{ $count }})"
                                            class="rounded border px-3 py-3 {{ $columnCount === $count ? 'border-black bg-gray-100' : 'border-gray-300 bg-white' }}">
                                        <div class="flex items-center justify-center gap-1">
                                            @for($i = 0; $i < $count; $i++)
                                                <div class="h-6 w-4 bg-gray-300"></div>
                                            @endfor
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <label class="flex items-center justify-between rounded bg-gray-100 p-2">
                            <span>কলাম ডিভাইডার</span>
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-emerald-600" wire:model.live="previewOptions.showColumnDivider">
                        </label>
                    </div>
                </div>

                <div>
                    <p class="border-t border-emerald-500 bg-emerald-50 p-2 font-bold">সহায়ক টুলস</p>
                    <div class="space-y-2 pt-2">
                        <button type="button" wire:click="shuffleQuestions" class="flex w-full items-center justify-between rounded bg-gray-100 p-3 transition hover:bg-emerald-600 hover:text-white">
                            <span>শাফল (সেট কোড তৈরী)</span>
                            <span class="rounded bg-white/80 px-2 py-1 text-xs font-bold text-gray-700">{{ $setCode }}</span>
                        </button>
                    </div>
                </div>

                <div>
                    <p class="border-t border-emerald-500 bg-emerald-50 p-2 font-bold">ব্র্যান্ডিং</p>
                    <div class="space-y-3 pt-2">
                        <label class="flex items-center justify-between rounded bg-gray-100 p-2">
                            <span>জলছাপ</span>
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-emerald-600" wire:model.live="previewOptions.showWatermark">
                        </label>

                        @if($previewOptions['showWatermark'])
                            <div class="space-y-3 rounded bg-gray-100 p-3">
                                <div>
                                    <label class="mb-1 block text-sm font-medium">জলছাপের লেখা</label>
                                    <input type="text" wire:model.live.debounce.300ms="watermarkText" class="w-full rounded border border-gray-300" placeholder="জলছাপের লেখা লিখুন">
                                </div>
                                <div>
                                    <div class="mb-1 flex justify-between text-sm">
                                        <span>স্বচ্ছতা</span>
                                        <span>{{ $watermarkOpacity }}%</span>
                                    </div>
                                    <input type="range" min="5" max="60" wire:model.live="watermarkOpacity" class="w-full">
                                </div>
                                <div>
                                    <div class="mb-1 flex justify-between text-sm">
                                        <span>সাইজ</span>
                                        <span>{{ $watermarkSize }}px</span>
                                    </div>
                                    <input type="range" min="16" max="72" wire:model.live="watermarkSize" class="w-full">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
