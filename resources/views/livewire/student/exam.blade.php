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
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow space-y-6">
            <div class="flex justify-between">
                <div class="font-semibold">Time Left: {{ gmdate('i:s', $timeLeft) }}</div>
                <div class="text-sm text-red-600">Auto-submitted when time is up.</div>
            </div>


            @foreach($questions as $index => $question)
                <div class="space-y-2">
                    <div class="prose max-w-none">{{ $this->banglaNumber($index + 1) }}. {!! $question->title !!}</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($question->options as $optIndex => $opt)
                            <label class="border rounded p-3 flex items-start space-x-2 cursor-pointer">
                                <input type="radio" class="mt-1" wire:model="selectedOptions.{{ $question->id }}" value="{{ $opt->id }}">
                                <span><span class="mr-2">({{ $this->optionLabel($optIndex) }})</span>{!! $opt->option_text !!}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="text-center">
                <button wire:click="submitExam" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>

            <div class="mb-4 prose max-w-none flex font-bold align-middle space-x-2"><span>{{ $this->banglaNumber($currentIndex + 1) }}.</span> <span>{!! $currentQuestion->title !!}</span></div>

            <ul class="space-y-2">
                @foreach($currentQuestion->options as $index => $opt)
                    <li>
                        <button wire:click="selectOption({{ $opt->id }})" class="border flex px-3 py-2 rounded w-full text-left @if($selectedOption == $opt->id) bg-indigo-100 @endif">
                            <span class="mr-2">({{ $this->optionLabel($index) }})</span>{!! $opt->option_text !!}
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
