<div>
    <div class="mb-4 flex flex-col sm:flex-row gap-2">
        <select wire:model.live="subjectId" class="border rounded px-3 py-2">
            <option value="">All Subjects</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="chapterId" class="border rounded px-3 py-2">
            <option value="">All Chapters</option>
            @foreach($chapters as $chapter)
                <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
            @endforeach
        </select>
        <button wire:click="loadRandom" class="bg-indigo-500 hover:bg-indigo-600 transition-colors text-white px-4 py-2 rounded">Load</button>
    </div>

    @if($current)
        <div class="mb-4 prose max-w-none font-bold text-lg">{!! $current->title !!}</div>

        @php
            $mcqOptions = [];
            // চেক করা হচ্ছে প্রশ্নটি MCQ কিনা এবং extra_content এ ডাটা আছে কিনা
            if (in_array($current->question_type, ['mcq', 'composite'])) {
                if (!empty($current->extra_content)) {
                    $parsed = is_string($current->extra_content) ? json_decode($current->extra_content, true) : $current->extra_content;
                    if (is_array($parsed)) {
                        $mcqOptions = $parsed;
                    }
                } elseif ($current->options && $current->options->isNotEmpty()) {
                    // পুরোনো প্রশ্নের ডাটা সাপোর্ট করার জন্য
                    $mcqOptions = $current->options->toArray();
                }
            }
        @endphp

        @if(!empty($mcqOptions))
            <ul class="space-y-2">
                @foreach($mcqOptions as $index => $opt)
                    @php
                        // JSON এর ক্ষেত্রে সাধারণত id থাকে না, তাই লুপের $index কেই id হিসেবে ধরা হলো
                        // আর পুরোনো ডাটার ক্ষেত্রে $opt['id'] ব্যবহার করা হবে
                        $optId = $opt['id'] ?? $index;
                        $isCorrect = !empty($opt['is_correct']) && $opt['is_correct'];
                        $optText = $opt['option_text'] ?? '';
                    @endphp

                    <li>
                        <button wire:click="selectOption('{{ $optId }}')"
                                class="border px-3 py-2 rounded w-full text-left transition-colors duration-200
                                       @if((string)$selectedOption === (string)$optId && $isCorrect) bg-green-100 border-green-500
                                       @elseif((string)$selectedOption === (string)$optId) bg-red-100 border-red-500
                                       @else hover:bg-gray-50
                                       @endif">
                            <div class="flex gap-2 items-center">
                                <span class="font-bold shrink-0">{{ mb_chr(2453 + $loop->index) }}.</span>
                                <div>{!! $optText !!}</div>
                            </div>
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-6">
            <button wire:click="loadRandom"
                    class="bg-blue-500 hover:bg-blue-600 transition-colors text-white px-5 py-2 rounded font-semibold shadow-sm">
                Next Question
            </button>
        </div>

        <script>
            // Rerender MathJax when Livewire updates content
            document.addEventListener('livewire:update', () => {
                if (window.MathJax) MathJax.typesetPromise();
            });
        </script>
    @endif
</div>
