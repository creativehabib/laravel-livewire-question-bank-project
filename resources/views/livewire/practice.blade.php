<div>
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
