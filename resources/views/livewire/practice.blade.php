<div>
    <div class="mb-4 flex flex-col sm:flex-row gap-2">
        <select wire:model="subjectId" class="border rounded px-3 py-2">
            <option value="">All Subjects</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
        <select wire:model="chapterId" class="border rounded px-3 py-2">
            <option value="">All Chapters</option>
            @foreach($chapters as $chapter)
                <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
            @endforeach
        </select>
        <button wire:click="loadRandom" class="bg-indigo-500 text-white px-4 py-2 rounded">Load</button>
    </div>

    @if($current)
        <div class="mb-4 prose max-w-none">{!! $current->title !!}</div>

        <ul class="space-y-2">
            @foreach($current->options as $opt)
                <li>
                    <button wire:click="selectOption({{ $opt->id }})"
                            class="border px-3 py-2 rounded w-full text-left
                                   @if($selectedOption == $opt->id && $opt->is_correct) bg-green-100
                                   @elseif($selectedOption == $opt->id) bg-red-100
                                   @endif">
                        {!! $opt->option_text !!}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            <button wire:click="loadRandom"
                    class="bg-blue-500 text-white px-4 py-2 rounded">Next Question</button>
        </div>

        <script>
            // Rerender MathJax when Livewire updates content
            document.addEventListener('livewire:update', () => {
                if (window.MathJax) MathJax.typesetPromise();
            });
        </script>
    @endif
</div>
