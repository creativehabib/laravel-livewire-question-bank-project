<div x-data>
    <form wire:submit.prevent="save" class="space-y-4">
        {{-- Subject --}}
        <div wire:ignore>
            <label>Subject</label>
            <select id="subject" class="border p-2 rounded w-full">
                <option value="">-- Select --</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" @selected($s->id == $subject_id)>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Chapter --}}
        <div wire:ignore>
            <label>Chapter</label>
            <select id="chapter" class="border p-2 rounded w-full">
                <option value="">-- Select --</option>
                @foreach($chapters as $c)
                    <option value="{{ $c->id }}" @selected($c->id == $chapter_id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Main Question --}}
        <div wire:ignore>
            <label>Question</label>
            <div id="editor" class="border min-h-32 p-2 rounded">{!! $title !!}</div>
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
                        {{-- Load saved HTML for each option --}}
                        <div id="opt_editor_{{ $i }}" class="min-h-200">
                            {!! $opt['option_text'] ?? '' !!}
                        </div>
                    </div>
                    <label class="flex items-center gap-1">
                        <input type="checkbox"
                               wire:model="options.{{ $i }}.is_correct"
                               @if(!empty($opt['is_correct']) && $opt['is_correct']) checked @endif>
                        Correct
                    </label>
                </div>
            @endforeach
        </div>

        {{-- Submit --}}
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Update Question
        </button>
    </form>
</div>

{{-- Quill + KaTeX + SweetAlert --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        let quillEditors = {};
        let tsSubject, tsChapter;

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
                }
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
                    }
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

            if (tsSubject) tsSubject.destroy();
            tsSubject = new TomSelect('#subject', {
                onChange: (value) => {
                    @this.set('subject_id', value);
                }
            });
            tsSubject.setValue(@json($subject_id));

            if (tsChapter) tsChapter.destroy();
            tsChapter = new TomSelect('#chapter', {
                onChange: (value) => {
                    @this.set('chapter_id', value);
                }
            });
            tsChapter.setValue(@json($chapter_id));

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

        window.addEventListener('chaptersUpdated', e => {
            if (!tsChapter) return;
            tsChapter.clearOptions();
            tsChapter.addOptions(e.detail.chapters);
            tsChapter.refreshOptions(false);
            tsChapter.setValue('');
        });

        if (document.readyState !== 'loading') {
            initEditors();
        } else {
            document.addEventListener('DOMContentLoaded', initEditors);
        }
        document.addEventListener('livewire:navigated', initEditors);
    </script>
@endpush
