<div x-data="{ questionType: @entangle('question_type') }" class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Subject --}}
            <div wire:ignore wire:key="subject-select-{{ $question->id }}">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                <select id="subject" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select --</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" @selected($s->id == $subject_id)>{{ $s->name }}</option>
                    @endforeach
                </select>
                @error('subject_id')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
            </div>

            {{-- Sub-Subject (Optional) --}}
            <div wire:ignore wire:key="subsubject-select-{{ $question->id }}">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sub-Subject (Optional)</label>
                <select id="sub_subject" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select --</option>
                    @foreach($subSubjects as $ss)
                        <option value="{{ $ss->id }}" @selected($ss->id == $sub_subject_id)>{{ $ss->name }}</option>
                    @endforeach
                </select>
                @error('sub_subject_id')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
            </div>

            {{-- Chapter (Required if Sub-Subject) --}}
            <div wire:ignore wire:key="chapter-select-{{ $question->id }}">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chapter</label>
                <select id="chapter" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select --</option>
                    @foreach($chapters as $c)
                        <option value="{{ $c->id }}" @selected($c->id == $chapter_id)>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('chapter_id')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Difficulty --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Difficulty</label>
                <select wire:model="difficulty" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
                @error('difficulty')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
            </div>

            {{-- Question Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question Type</label>
                <select wire:model="question_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="mcq">MCQ</option>
                    <option value="cq">CQ</option>
                    <option value="short">Short</option>
                </select>
                @error('question_type')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
            </div>

            {{-- Marks --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marks</label>
                <input type="number" step="0.5" min="0" wire:model="marks" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500" />
                @error('marks')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Question Description (Optional) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
            <textarea wire:model.defer="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            @error('description')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
        </div>

        {{-- Main Question --}}
        <div wire:ignore>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question</label>
            <div id="editor" class="border border-gray-300 dark:border-gray-600 min-h-32 p-2 dark:bg-gray-700 dark:text-gray-100">{!! $title !!}</div>
            @error('title')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
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
        <div class="space-y-4" x-show="questionType === 'mcq'">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Options</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($options as $i => $opt)
                    <div wire:key="opt-{{ $i }}" class="flex items-start gap-2">
                        <div wire:ignore class="flex-1">
                            <div id="opt_editor_{{ $i }}" class="border border-gray-300 dark:border-gray-600 min-h-24 p-2 dark:bg-gray-700 dark:text-gray-100">{!! $opt['option_text'] ?? '' !!}</div>
                        </div>
                        <label class="flex items-center gap-1 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" wire:model="options.{{ $i }}.is_correct" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600" @if(!empty($opt['is_correct']) && $opt['is_correct']) checked @endif>
                            <span>Correct</span>
                        </label>
                    </div>
                @endforeach
            </div>
            @error('options')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
            @error('options.*.option_text')<span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            Update Question
        </button>
    </form>
</div>

{{-- Quill + KaTeX + SweetAlert --}}
@push('scripts')
    <script>
        let quillEditors = {};
        let tsSubject, tsSubSubject, tsChapter;

        function initEditors() {
            const main = document.getElementById('editor');

            if (main && !main.classList.contains('ql-container')) {
                quillEditors = {};
                window.quillEditors = quillEditors;

                const toolbarOptions = [
                    ['bold', 'italic'],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    ['customMath'],
                    ['clean'],
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
                    if (el.classList.contains('ql-container')) return;
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
            }

            if (tsSubject) tsSubject.destroy();
            tsSubject = new TomSelect('#subject', {
                onChange: (value) => {
                    @this.set('subject_id', value);
                }
            });
            tsSubject.setValue(@json($subject_id), true);

            if (tsSubSubject) tsSubSubject.destroy();
            tsSubSubject = new TomSelect('#sub_subject', {
                onChange: (value) => {
                    @this.set('sub_subject_id', value);
                }
            });
            tsSubSubject.setValue(@json($sub_subject_id), true);

            if (tsChapter) {
                tsChapter.destroy();
                tsChapter = null;
            }
            const chapterEl = document.getElementById('chapter');
            if (chapterEl) {
                tsChapter = new TomSelect('#chapter', {
                    onChange: (value) => {
                        @this.set('chapter_id', value);
                    }
                });
                tsChapter.setValue(@json($chapter_id), true);
            }

            if (window.tsTags) window.tsTags.destroy();
            window.tsTags = new TomSelect('#tags', {
                plugins: ['remove_button'],
                persist: false,
                create: true,
                onChange: (values) => {
                    @this.set('tagIds', values);
                }
            });
            window.tsTags.setValue(@json($tagIds), true);
            @this.set('tagIds', window.tsTags.items);
        }

        window.addEventListener('subSubjectsUpdated', e => {
            if (!tsSubSubject) return;
            tsSubSubject.clearOptions();
            tsSubSubject.addOptions(e.detail.subSubjects);
            tsSubSubject.refreshOptions(false);
            tsSubSubject.setValue('');
        });

        window.addEventListener('chaptersUpdated', e => {
            if (tsChapter) {
                tsChapter.destroy();
                tsChapter = null;
            }

            const chapterEl = document.getElementById('chapter');
            if (!chapterEl) return;

            chapterEl.options.length = 0;
            chapterEl.append(new Option('-- Select --', ''));
            tsChapter = new TomSelect('#chapter', {
                onChange: (value) => {
                    @this.set('chapter_id', value);
                }
            });
            tsChapter.addOptions(e.detail.chapters);
            tsChapter.refreshOptions(false);
            tsChapter.setValue('');
        });

        window.addEventListener('reset-selects', () => {
            tsSubject?.clear(true);
            tsSubject?.setValue('', true);

            tsSubSubject?.clear(true);
            tsSubSubject?.setValue('', true);

            tsChapter?.clear(true);
            tsChapter?.setValue('', true);
        });

        document.addEventListener('livewire:load', initEditors);
        document.addEventListener('livewire:navigated', initEditors);
        document.addEventListener('livewire:update', initEditors);
    </script>
@endpush
