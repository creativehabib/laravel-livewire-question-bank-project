<div x-data="{ questionType: @entangle('question_type') }">
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

        {{-- Sub-Subject (Optional) --}}
        <div>
            <label>Sub-Subject (Optional)</label>
            <select wire:model="sub_subject_id" class="border p-2 rounded w-full">
                <option value="">-- Select --</option>
                @foreach($subSubjects as $ss)
                    <option value="{{ $ss->id }}">{{ $ss->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Chapter (Optional) --}}
        <div>
            <label>Chapter (Optional)</label>
            <select wire:model="chapter_id" class="border p-2 rounded w-full" {{ $sub_subject_id ? '' : 'disabled' }}>
                <option value="">-- Select --</option>
                @foreach($chapters as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Difficulty --}}
            <div>
                <label>Difficulty</label>
                <select wire:model="difficulty" class="border p-2 rounded w-full">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
                @error('difficulty')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            {{-- Question Type --}}
            <div>
                <label>Question Type</label>
                <select wire:model="question_type" class="border p-2 rounded w-full">
                    <option value="mcq">MCQ</option>
                    <option value="cq">CQ</option>
                    <option value="short">Short</option>
                </select>
                @error('question_type')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            {{-- Marks --}}
            <div>
                <label>Marks</label>
                <input type="number" step="0.5" min="0" wire:model="marks" class="border p-2 rounded w-full" />
                @error('marks')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Main Question --}}
        <div wire:ignore>
            <label>Question</label>
            <div id="editor" class="border min-h-32 p-2 rounded"></div>
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
        <div class="space-y-2" x-show="questionType === 'mcq'">
            <label>Options</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($options as $i => $opt)
                    <div wire:key="opt-{{ $i }}" class="flex items-center gap-2">
                        <div wire:ignore class="flex-1">
                            <div id="opt_editor_{{ $i }}" class="min-h-200"></div>
                        </div>
                        <label>
                            <input type="checkbox" wire:model="options.{{ $i }}.is_correct"> Correct
                        </label>
                    </div>
                @endforeach
            </div>
            @error('options')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            @error('options.*.option_text')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
            Save Question
        </button>
    </form>
</div>

{{-- Quill + Formula JS --}}
@push('scripts')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script>
        let quillEditors = {};

        function initEditors() {
            const toolbarOptions = [
                ['bold', 'italic'],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['formula'],
                ['clean']
            ];

            // Question Editor
            let mainEditor = new Quill('#editor', {
                theme: 'snow',
                modules: { formula: true, toolbar: toolbarOptions }
            });
            mainEditor.root.innerHTML = @json($title); // ✅ edit করলে value বসাবে
            quillEditors['title'] = mainEditor;

            mainEditor.on('text-change', function () {
                @this.set('title', mainEditor.root.innerHTML);
            });

            // Options Editors
            @foreach($options as $i => $opt)
            let optEditor{{ $i }} = new Quill('#opt_editor_{{ $i }}', {
                theme: 'snow',
                modules: { formula: true, toolbar: toolbarOptions }
            });
            optEditor{{ $i }}.root.innerHTML = @json($options[$i]['option_text'] ?? '');
            quillEditors['option_{{ $i }}'] = optEditor{{ $i }};
            optEditor{{ $i }}.on('text-change', function () {
                @this.set('options.{{ $i }}.option_text', optEditor{{ $i }}.root.innerHTML);
            });
            @endforeach

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

        if (document.readyState !== 'loading') {
            initEditors();
        } else {
            document.addEventListener('DOMContentLoaded', initEditors);
        }
        document.addEventListener('livewire:navigated', initEditors);
        document.addEventListener('livewire:update', initEditors);
    </script>
@endpush
