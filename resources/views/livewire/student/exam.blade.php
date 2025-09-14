<div wire:poll.1s="tick" class="space-y-6">
    @if(!$examStarted && !$examFinished)
        <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">
            <select wire:model="subjectId" class="w-full border rounded px-3 py-2">
                <option value="">বিষয় সিলেক্ট করো</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>

            <select wire:model="chapterId" class="w-full border rounded px-3 py-2">
                <option value="">অধ্যায় সিলেক্ট করো</option>
                @foreach($chapters as $chapter)
                    <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                @endforeach
            </select>

            <input type="number" wire:model="totalQuestions" class="w-full border rounded px-3 py-2" />
            <input type="number" wire:model="duration" class="w-full border rounded px-3 py-2" />

            <button wire:click="startExam" class="w-full bg-indigo-500 text-white py-2 rounded">পরীক্ষা শুরু করো</button>
        </div>
    @elseif($examStarted)
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="flex justify-between mb-4">
                <div>প্রশ্ন {{ $currentIndex + 1 }} / {{ $questions->count() }}</div>
                <div>সময়: {{ gmdate('i:s', $timeLeft) }}</div>
            </div>

            <div class="mb-4 prose max-w-none">{!! $currentQuestion->title !!}</div>

            <ul class="space-y-2">
                @foreach($currentQuestion->options as $opt)
                    <li>
                        <button wire:click="selectOption({{ $opt->id }})" class="border px-3 py-2 rounded w-full text-left @if($selectedOption == $opt->id) bg-indigo-100 @endif">
                            {!! $opt->option_text !!}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4">
                <button wire:click="next" class="bg-blue-500 text-white px-4 py-2 rounded" @disabled(!$selectedOption)>Next</button>
            </div>

            <script>
                document.addEventListener('livewire:update', () => {
                    if (window.MathJax) MathJax.typesetPromise();
                });
            </script>
        </div>
    @elseif($examFinished)
        <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow text-center space-y-4">
            <div class="text-2xl font-semibold">তোমার স্কোর: {{ $score }} / {{ $questions->count() }}</div>
            <button wire:click="resetExam" class="bg-indigo-500 text-white px-4 py-2 rounded">নতুন পরীক্ষা</button>
        </div>
    @endif
</div>
