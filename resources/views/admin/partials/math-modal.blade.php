<div x-data="{ showMathModal: false, targetEditor: null, latex: '' }"
     x-init="
        window.openMathPopup = function(editorKey) {
            targetEditor = editorKey;
            latex = '';
            showMathModal = true;
            $nextTick(() => { renderLatex(); });
        };

        function renderLatex() {
            const preview = document.getElementById('latexPreview');
            if (!preview) return;
            try {
                katex.render(latex, preview, { throwOnError: false });
            } catch (err) {
                preview.innerHTML = '<span class=\'text-red-500\'>Invalid LaTeX</span>';
            }
        }

        $watch('latex', () => renderLatex());
     "
     class="relative">

    {{-- আপনার Form --}}
    <form wire:submit.prevent="save" class="space-y-4">
        <div wire:ignore>
            <label>Question</label>
            <div id="editor" class="min-h-200">{!! $title !!}</div>
        </div>

        <div class="space-y-2">
            <label>Options</label>
            @foreach($options as $i => $opt)
                <div wire:key="opt-{{ $i }}" class="flex items-center gap-2">
                    <div wire:ignore class="flex-1">
                        <div id="opt_editor_{{ $i }}" class="min-h-200">
                            {!! $opt['option_text'] ?? '' !!}
                        </div>
                    </div>
                    <label>
                        <input type="checkbox" wire:model="options.{{ $i }}.is_correct"> Correct
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Question</button>
    </form>


    {{-- Tailwind Modal --}}
    <div x-show="showMathModal"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         x-transition>
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <h2 class="text-lg font-bold mb-2">Mathematics in TeX</h2>

            <label class="text-sm font-semibold">Write your TeX here</label>
            <textarea x-model="latex" rows="3"
                      class="w-full border rounded p-2 mt-1 font-mono text-sm"
                      placeholder="e.g. x = \\frac{-b \\pm \\sqrt{b^2-4ac}}{2a}"></textarea>

            <div id="latexPreview" class="min-h-[80px] mt-4 text-center text-xl"></div>

            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" @click="showMathModal=false"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>

                <button type="button"
                        @click="
                            const editor = quillEditors[targetEditor];
                            if(editor){
                                const index = editor.getSelection(true).index;
                                editor.insertEmbed(index, 'formula', latex, Quill.sources.USER);
                                editor.setSelection(index + 1);
                            }
                            showMathModal = false;
                        "
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Insert</button>
            </div>
        </div>
    </div>
</div>


{{-- Quill + KaTeX --}}
@push('scripts')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>

    <script>
        let quillEditors = {};

        document.addEventListener('livewire:navigated', () => {
            const toolbarOptions = [
                ['bold', 'italic'],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['formula'],
                ['customMath'],
                ['clean']
            ];

            // Main editor
            let mainEditor = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    formula: true,
                    toolbar: {
                        container: toolbarOptions,
                        handlers: {
                            customMath: function () {
                                openMathPopup('title');
                            }
                        }
                    }
                }
            });
            quillEditors['title'] = mainEditor;
            mainEditor.on('text-change', () => @this.set('title', mainEditor.root.innerHTML));

            // Options editors
            document.querySelectorAll('[id^="opt_editor_"]').forEach(el => {
                let index = el.id.replace('opt_editor_', '');
                let optEditor = new Quill(`#${el.id}`, {
                    theme: 'snow',
                    modules: {
                        formula: true,
                        toolbar: {
                            container: toolbarOptions,
                            handlers: {
                                customMath: function () {
                                    openMathPopup(`option_${index}`);
                                }
                            }
                        }
                    }
                });
                quillEditors[`option_${index}`] = optEditor;
                optEditor.on('text-change', () =>
                    @this.set(`options.${index}.option_text`, optEditor.root.innerHTML)
                );
            });

            document.querySelectorAll('.ql-customMath').forEach(btn => btn.innerHTML = '∑');
        });
    </script>
@endpush
