<div x-data class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Subject --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
            <select wire:model="subject_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Select --</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Chapter --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chapter</label>
            <select wire:model="chapter_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Select --</option>
                @foreach($chapters as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Main Question --}}
        <div wire:ignore>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question</label>
            <div id="editor" class="mt-1 border border-gray-300 dark:border-gray-600 rounded-md min-h-32 p-2 dark:bg-gray-700 dark:text-gray-100"></div>
        </div>

        {{-- Difficulty --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Difficulty</label>
            <select wire:model="difficulty" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
        </div>

        {{-- Tags --}}
        <div wire:ignore>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
            <select id="tags" class="w-full mt-1" multiple>
                @foreach($allTags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, $tagIds) ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Options --}}
        <div class="space-y-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Options</label>
            @foreach($options as $i => $opt)
                <div wire:key="opt-{{ $i }}" class="flex items-start gap-2">
                    <div wire:ignore class="flex-1">
                        <div id="opt_editor_{{ $i }}" class="border border-gray-300 dark:border-gray-600 rounded-md min-h-24 p-2 dark:bg-gray-700 dark:text-gray-100"></div>
                    </div>
                    <label class="flex items-center gap-1 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" wire:model="options.{{ $i }}.is_correct" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                        <span>Correct</span>
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
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
