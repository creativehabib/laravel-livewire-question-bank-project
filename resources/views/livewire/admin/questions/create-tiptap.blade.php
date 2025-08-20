<div x-data>
    <form wire:submit.prevent="save" class="space-y-4">
        {{-- Question --}}
        <div wire:ignore>
            <label>Question</label>
            <div id="editor" class="border rounded min-h-[200px] p-2 bg-white"></div>
        </div>

        {{-- Options --}}
        <div class="space-y-2">
            <label>Options</label>
            @foreach($options as $i => $opt)
                <div wire:key="opt-{{ $i }}" class="flex items-center gap-2">
                    <div wire:ignore class="flex-1">
                        <div id="opt_editor_{{ $i }}" class="border rounded min-h-[100px] p-2 bg-white">
                            {!! $opt['option_text'] ?? '' !!}
                        </div>
                    </div>
                    <label>
                        <input type="checkbox" wire:model="options.{{ $i }}.is_correct"> Correct
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
            Save Question
        </button>
    </form>
</div>

@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">

    <script type="module">
        import { Editor } from "@tiptap/core"
        import StarterKit from "@tiptap/starter-kit"
        import Mathematics from "@tiptap/extension-mathematics"

        let editors = {}

        document.addEventListener("livewire:navigated", () => {
            // --- Main Question Editor ---
            let mainEditor = new Editor({
                element: document.querySelector('#editor'),
                extensions: [
                    StarterKit,
                    Mathematics.configure({
                        katexRenderOptions: { throwOnError: false },
                    }),
                ],
                content: @this.get('title') || '',
                onUpdate: ({ editor }) => {
                @this.set('title', editor.getHTML())
                },
            })
            editors['title'] = mainEditor

            // --- Options Editors ---
            document.querySelectorAll('[id^="opt_editor_"]').forEach(el => {
                let index = el.id.replace('opt_editor_', '')
                let optEditor = new Editor({
                    element: el,
                    extensions: [
                        StarterKit,
                        Mathematics.configure({
                            katexRenderOptions: { throwOnError: false },
                        }),
                    ],
                    content: @this.get(`options.${index}.option_text`) || '',
                    onUpdate: ({ editor }) => {
                        @this.set(`options.${index}.option_text`, editor.getHTML())
                    },
                })
                editors[`option_${index}`] = optEditor
            })
        })
    </script>
@endpush
