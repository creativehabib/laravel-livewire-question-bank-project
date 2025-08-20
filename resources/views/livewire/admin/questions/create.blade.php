<div x-data>
    <form wire:submit.prevent="save" class="space-y-4">
        {{-- Subject --}}
        <div>
            <label>Subject</label>
            <select wire:model="subject_id" class="border p-2 rounded w-full">
                <option value="">-- Select --</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Chapter --}}
        <div>
            <label>Chapter</label>
            <select wire:model="chapter_id" class="border p-2 rounded w-full">
                <option value="">-- Select --</option>
                @foreach($chapters as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Main Question --}}
        <div wire:ignore>
            <label>Question</label>
            <div id="editor" class="border min-h-32 p-2 rounded"></div>
        </div>

        {{-- Difficulty --}}
        <div>
            <label>Difficulty</label>
            <select wire:model="difficulty" class="border p-2 rounded">
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
        </div>

        {{-- Tags --}}
        <div wire:ignore>
            <label>Tags</label>
            <select id="tags" class="w-full" multiple>
                @foreach($allTags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, $tagIds) ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Options --}}
        <div class="space-y-2">
            <label>Options</label>
            @foreach($options as $i => $opt)
                <div wire:key="opt-{{ $i }}" class="flex items-center gap-2">
                    <div wire:ignore class="flex-1">
                        <div id="opt_editor_{{ $i }}" class="border min-h-24 p-2 rounded"></div>
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

{{-- Quill + KaTeX + SweetAlert --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        let quillEditors = {};

        function initEditors() {
            window.quillEditors = {};
            const toolbarOptions = [
                ['bold', 'italic'],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['customMath'], // আমাদের কাস্টম popup button
                ['clean']
            ];

            // --- Main Editor ---
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
                },
                placeholder: 'Compose an epic...',
            });
            quillEditors['title'] = mainEditor;

            mainEditor.on('text-change', function () {
                @this.set('title', mainEditor.root.innerHTML);
            });

            // --- Options Editors ---
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
                    },
                    placeholder: 'Compose an epic...',
                });
                quillEditors[`option_${index}`] = optEditor;

                optEditor.on('text-change', function () {
                    @this.set(`options.${index}.option_text`, optEditor.root.innerHTML);
                });
            });

            // Custom button icon
            document.querySelectorAll('.ql-customMath').forEach(btn => {
                btn.innerHTML = '∑';
            });

            if (window.tsTags) window.tsTags.destroy();
            window.tsTags = new TomSelect('#tags', {
                plugins: ['remove_button'],
                persist: false,
                create: true,
                onChange: (values) => {
                    @this.set('tagIds', values);
                }
            });
            @this.set('tagIds', window.tsTags.items);
        }

        if (document.readyState !== 'loading') {
            initEditors();
        } else {
            document.addEventListener('DOMContentLoaded', initEditors);
        }
        document.addEventListener('livewire:navigated', initEditors);
    </script>
@endpush
