<div x-data="{ questionType: @entangle('question_type') }" class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <form wire:submit.prevent="save" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div wire:ignore wire:key="subject-select-{{ $question->id }}">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                <select id="subject" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500">
                    <option value="">-- Select --</option>
                    @foreach($subjects as $s) <option value="{{ $s->id }}" @selected($s->id == $subject_id)>{{ $s->name }}</option> @endforeach
                </select>
                @error('subject_id')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            <div wire:ignore wire:key="subsubject-select-{{ $question->id }}">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sub-Subject</label>
                <select id="sub_subject" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500">
                    <option value="">-- Select --</option>
                    @foreach($subSubjects as $ss) <option value="{{ $ss->id }}" @selected($ss->id == $sub_subject_id)>{{ $ss->name }}</option> @endforeach
                </select>
            </div>

            <div wire:ignore wire:key="chapter-select-{{ $question->id }}">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chapter</label>
                <select id="chapter" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500">
                    <option value="">-- Select --</option>
                    @foreach($chapters as $c) <option value="{{ $c->id }}" @selected($c->id == $chapter_id)>{{ $c->name }}</option> @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Difficulty</label>
                <select wire:model="difficulty" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question Type</label>
                <select wire:model.live="question_type" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500">
                    <option value="mcq">MCQ</option>
                    <option value="cq">CQ (Creative)</option>
                    <option value="short">Short</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Marks</label>
                <input type="number" step="0.5" min="0" wire:model.live="marks"
                       x-bind:readonly="questionType === 'cq'"
                       x-bind:class="questionType === 'cq' ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed' : 'bg-white dark:bg-gray-700'"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:text-gray-200 focus:ring-indigo-500" />
                <span x-show="questionType === 'cq'" class="text-xs text-purple-600 font-bold">* Auto calculated for CQ.</span>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description / Solution (Optional)</label>
            <div wire:ignore><div id="description_editor" class="border border-gray-300 min-h-24 p-2 dark:bg-gray-700 dark:text-gray-100">{!! $description !!}</div></div>
        </div>

        <div wire:ignore>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Main Question / Stimulus (উদ্দীপক)</label>
            <div id="editor" class="border border-gray-300 min-h-32 p-2 dark:bg-gray-700 dark:text-gray-100">{!! $title !!}</div>
            @error('title')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
        </div>

        <div wire:ignore>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
            <select id="tags" class="w-full mt-1" multiple>
                @foreach($allTags as $tag) <option value="{{ $tag->id }}" {{ in_array($tag->id, $tagIds) ? 'selected' : '' }}>{{ $tag->name }}</option> @endforeach
            </select>
        </div>

        {{-- MCQ Section (Redesigned like CQ) --}}
        <div class="space-y-4" x-show="questionType === 'mcq'" x-transition>
            <div class="flex justify-between items-center border-b pb-2">
                <label class="block text-sm font-bold text-blue-700">MCQ Options (বহুনির্বাচনী অপশন)</label>
            </div>

            <div class="grid gap-4 bg-blue-50 p-4 rounded-lg border border-blue-100">
                @php $mcqLabels = ['ক', 'খ', 'গ', 'ঘ']; @endphp

                @foreach($options as $i => $opt)
                    <div wire:key="opt-{{ $i }}" class="bg-white p-3 rounded border border-gray-200 shadow-sm relative transition-all hover:border-blue-300">

                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <span class="w-12 font-bold text-center bg-blue-100 text-blue-800 rounded-md py-1 shadow-sm">
                                    {{ $mcqLabels[$i] ?? ($i+1) }}
                                </span>
                            </div>

                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-md hover:bg-green-50 hover:border-green-300 transition-colors">
                                <input type="checkbox" wire:model="options.{{ $i }}.is_correct" class="rounded text-green-600 focus:ring-green-500 h-5 w-5" @if(!empty($opt['is_correct']) && $opt['is_correct']) checked @endif>
                                <span class="text-sm font-bold text-gray-700 select-none">Correct Answer</span>
                            </label>
                        </div>

                        <div wire:ignore>
                            <div id="opt_editor_{{ $i }}" class="min-h-24 p-2 border border-gray-300 rounded-md bg-white">{!! $opt['option_text'] ?? '' !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('options.*.option_text')<span class="text-sm text-red-600 font-bold">* সবগুলো অপশন পূরণ করা আবশ্যক।</span>@enderror
        </div>

        {{-- CQ Section --}}
        <div class="space-y-4" x-show="questionType === 'cq'" style="display: none;" x-transition>
            <div class="flex justify-between items-center border-b pb-2">
                <label class="block text-sm font-bold text-purple-700">Creative Questions (সৃজনশীল অংশ)</label>
                <button type="button" wire:click="addCqPart" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded shadow">+ Add New Part</button>
            </div>

            <div class="grid gap-4 bg-purple-50 p-4 rounded-lg border border-purple-100">
                @foreach($cq as $index => $part)
                    <div wire:key="cq-part-{{ $part['id'] ?? $index }}" class="bg-white p-3 rounded border relative">
                        <button type="button" wire:click="removeCqPart({{ $index }})" class="absolute top-2 right-2 text-red-500 font-bold">✕</button>

                        <div class="flex items-center gap-3 mb-2 pr-8">
                            <input type="text" wire:model.live="cq.{{ $index }}.label" class="w-16 font-bold text-center bg-purple-100 text-purple-800 rounded-md border-0 py-1" placeholder="নং">
                            <div class="flex items-center gap-1 ml-auto">
                                <span class="text-sm text-gray-500">Marks:</span>
                                <input type="number" wire:model.live="cq.{{ $index }}.marks" class="w-20 rounded-md border-gray-300 text-center py-1" min="0" step="0.5">
                            </div>
                        </div>

                        <div wire:ignore>
                            <div id="cq_editor_{{ $part['id'] ?? $index }}" data-index="{{ $index }}" class="cq-dynamic-editor min-h-24 p-2">{!! $part['text'] ?? '' !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md shadow-md w-full sm:w-auto">Update Question</button>
    </form>
</div>

@push('scripts')
    <script>
        // গ্লোবাল ভ্যারিয়েবলগুলো যেন কনফ্লিক্ট না করে সেজন্য window অবজেক্ট ব্যবহার করা হলো
        window.quillEditors = window.quillEditors || {};
        window.tsSubject = window.tsSubject || null;
        window.tsSubSubject = window.tsSubSubject || null;
        window.tsChapter = window.tsChapter || null;
        window.tsTags = window.tsTags || null;

        function initEditors() {
            const toolbarOptions = [['bold', 'italic'], [{ 'script': 'sub' }, { 'script': 'super' }], ['customMath'], ['clean']];

            // 1. Main Stimulus Editor
            const mainEl = document.getElementById('editor');
            if (mainEl && !mainEl.classList.contains('ql-container')) {
                let mainEditor = new Quill('#editor', { theme: 'snow', modules: { formula: true, toolbar: { container: toolbarOptions, handlers: { customMath: function () { openMathPopup('title'); } } } }});
                window.quillEditors['title'] = mainEditor;
                mainEditor.on('text-change', () => @this.set('title', mainEditor.root.innerHTML));
            }

            // 2. Description Editor
            const descEl = document.getElementById('description_editor');
            if (descEl && !descEl.classList.contains('ql-container')) {
                let descEditor = new Quill('#description_editor', { theme: 'snow', modules: { formula: true, toolbar: { container: toolbarOptions, handlers: { customMath: function () { openMathPopup('description'); } } } }});
                window.quillEditors['description'] = descEditor;
                descEditor.on('text-change', () => @this.set('description', descEditor.root.innerHTML));
            }

            // 3. MCQ Option Editors
            document.querySelectorAll('[id^="opt_editor_"]').forEach(el => {
                if (el.classList.contains('ql-container')) return;
                let idx = el.id.replace('opt_editor_', '');
                let optEditor = new Quill(`#${el.id}`, { theme: 'snow', modules: { formula: true, toolbar: { container: toolbarOptions, handlers: { customMath: function () { openMathPopup(`option_${idx}`); } } } }});
                window.quillEditors[`option_${idx}`] = optEditor;
                optEditor.on('text-change', () => @this.set(`options.${idx}.option_text`, optEditor.root.innerHTML));
            });

            // 4. Dynamic CQ Editors
            document.querySelectorAll('.cq-dynamic-editor').forEach(el => {
                if (el.classList.contains('ql-container')) return;
                let uId = el.id.replace('cq_editor_', '');
                let dIndex = el.getAttribute('data-index');

                let cqEditor = new Quill(`#${el.id}`, { theme: 'snow', modules: { formula: true, toolbar: { container: toolbarOptions, handlers: { customMath: function () { openMathPopup(`cq_${uId}`); } } } }});
                window.quillEditors[`cq_${uId}`] = cqEditor;
                cqEditor.on('text-change', () => @this.set(`cq.${dIndex}.text`, cqEditor.root.innerHTML));
            });

            document.querySelectorAll('.ql-customMath').forEach(btn => { btn.innerHTML = '∑'; });

            // 5. TomSelects Initialization
            if (window.tsSubject) { window.tsSubject.destroy(); window.tsSubject = null; }
            const subjectEl = document.getElementById('subject');
            if (subjectEl) window.tsSubject = new TomSelect(subjectEl, { onChange: (v) => @this.set('subject_id', v) });

            if (window.tsSubSubject) { window.tsSubSubject.destroy(); window.tsSubSubject = null; }
            const subSubjectEl = document.getElementById('sub_subject');
            if (subSubjectEl) window.tsSubSubject = new TomSelect(subSubjectEl, { onChange: (v) => @this.set('sub_subject_id', v) });

            if (window.tsChapter) { window.tsChapter.destroy(); window.tsChapter = null; }
            const chapterEl = document.getElementById('chapter');
            if (chapterEl) window.tsChapter = new TomSelect(chapterEl, { onChange: (v) => @this.set('chapter_id', v) });

            if (window.tsTags) { window.tsTags.destroy(); window.tsTags = null; }
            const tagsEl = document.getElementById('tags');
            if (tagsEl) window.tsTags = new TomSelect(tagsEl, { plugins: ['remove_button'], persist: false, create: true, onChange: (v) => @this.set('tagIds', v) });
        }

        // ইভেন্ট লিসেনারগুলো যেন একাধিকবার কল না হয়, তাই একটি ফ্ল্যাগ ব্যবহার করা হলো
        if (!window.hasRegisteredQuestionEvents) {

            window.addEventListener('subSubjectsUpdated', e => {
                if (window.tsSubSubject) {
                    window.tsSubSubject.clear(true);
                    window.tsSubSubject.clearOptions();
                    window.tsSubSubject.addOption({value: '', text: '-- Select --'});
                    window.tsSubSubject.addOptions(e.detail.subSubjects);
                    window.tsSubSubject.refreshOptions(false);
                }
            });

            window.addEventListener('chaptersUpdated', e => {
                if (window.tsChapter) {
                    window.tsChapter.clear(true);
                    window.tsChapter.clearOptions();
                    window.tsChapter.addOption({value: '', text: '-- Select --'});
                    window.tsChapter.addOptions(e.detail.chapters);
                    window.tsChapter.refreshOptions(false);
                }
            });

            window.addEventListener('reset-selects', () => {
                window.tsSubject?.clear(true);
                window.tsSubSubject?.clear(true);
                window.tsChapter?.clear(true);
            });

            window.addEventListener('refresh-editors', () => setTimeout(initEditors, 50));

            document.addEventListener('livewire:load', initEditors);
            document.addEventListener('livewire:navigated', initEditors);
            document.addEventListener('livewire:update', () => setTimeout(initEditors, 50));

            // পেইজ থেকে বের হয়ে যাওয়ার সময় TomSelect ধ্বংস করে দেওয়া, যেন আগের ডাটা ক্যাশ না হয়
            document.addEventListener('livewire:navigating', () => {
                if (window.tsSubject) { window.tsSubject.destroy(); window.tsSubject = null; }
                if (window.tsSubSubject) { window.tsSubSubject.destroy(); window.tsSubSubject = null; }
                if (window.tsChapter) { window.tsChapter.destroy(); window.tsChapter = null; }
                if (window.tsTags) { window.tsTags.destroy(); window.tsTags = null; }
            });

            window.hasRegisteredQuestionEvents = true;
        }
    </script>
@endpush
