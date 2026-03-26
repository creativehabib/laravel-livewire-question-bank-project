<div x-data="{ questionType: @entangle('question_type') }" class="max-w-5xl mx-auto p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">

    <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $formTitle }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $formDescription }}</p>
    </div>

    <style>
        .cke_chrome {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            overflow: hidden;
        }
        .dark .cke_chrome {
            border-color: #4b5563 !important;
        }
    </style>

    <form wire:submit.prevent="save" class="space-y-8">

        <div class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-lg border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" class="text-indigo-500"><path d="M496 128v16a8 8 0 0 1-8 8h-24v12c0 6.627-5.373 12-12 12H60c-6.627 0-12-5.373-12-12v-12H24a8 8 0 0 1-8-8v-16a8 8 0 0 1 8-8h22.758c5.441-26.657 20.301-49.851 40.718-67.653C104.992 40.404 115.309 32 128 32h256c12.691 0 23.008 8.404 40.524 20.347C444.941 70.149 459.801 93.343 465.242 120H488a8 8 0 0 1 8 8zM176 80c-8.837 0-16 7.163-16 16v16h256v-16c0-8.837-7.163-16-16-16H176zm-56 304h272v108c0 6.627-5.373 12-12 12H132c-6.627 0-12-5.373-12-12V384zm316-208H76c-6.627 0-12 5.373-12 12v152c0 6.627 5.373 12 12 12h360c6.627 0 12-5.373 12-12V188c0-6.627-5.373-12-12-12z"></path></svg>
                Categorization
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div wire:ignore wire:key="subject-select-{{ $question->id ?? 'create' }}">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
                    <select id="subject" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $s) <option value="{{ $s->id }}" @selected($s->id == $subject_id)>{{ $s->name }}</option> @endforeach
                    </select>
                    @error('subject_id')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div wire:ignore wire:key="subsubject-select-{{ $question->id ?? 'create' }}">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chapter</label>
                    <select id="chapter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">-- Select Chapter --</option>
                        @foreach($chapters as $ss) <option value="{{ $ss->id }}" @selected($ss->id == $chapter_id)>{{ $ss->name }}</option> @endforeach
                    </select>
                </div>

                <div wire:ignore wire:key="topic-select-{{ $question->id ?? 'create' }}">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Topic</label>
                    <select id="topic" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">-- Select Topic --</option>
                        @foreach($topics as $c) <option value="{{ $c->id }}" @selected($c->id == $topic_id)>{{ $c->name }}</option> @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Difficulty Level</label>
                <div class="relative">
                    <select wire:model="difficulty" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200 appearance-none">
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Question Type</label>
                <select wire:model.live="question_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200">
                    <option value="mcq">Multiple Choice (MCQ)</option>
                    <option value="cq">Creative Question (CQ)</option>
                    <option value="short">Short Question</option>
                    <option value="written">Written Question (লিখিত)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Marks</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="number" step="0.5" min="0" wire:model.live="marks"
                           x-bind:readonly="questionType === 'cq'"
                           x-bind:class="questionType === 'cq' ? 'bg-gray-100 dark:bg-gray-600 text-gray-500 cursor-not-allowed' : 'bg-white dark:bg-gray-700'"
                           class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-gray-200 pr-12" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Pts</span>
                    </div>
                </div>
                <span x-show="questionType === 'cq'" class="text-xs text-purple-600 font-medium mt-1 block flex items-center gap-1"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg> Auto calculated for CQ</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div wire:ignore>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tags <span class="text-gray-400 font-normal">(Type and press enter)</span></label>
                <select id="tags" class="w-full" multiple>
                    @foreach($allTags as $tag) <option value="{{ $tag->id }}" {{ in_array($tag->id, $tagIds) ? 'selected' : '' }}>{{ $tag->name }}</option> @endforeach
                </select>
            </div>

            <div wire:ignore>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Audience / Exam Category <span class="text-red-500">*</span></label>
                <select id="exam_categories" class="w-full" multiple placeholder="সিলেক্ট করুন (Job, Admission, Class 9)...">
                    @foreach($allExamCategories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, $exam_category_ids) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('exam_category_ids')<span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>@enderror
            </div>
        </div>

        <x-slug-input table="questions" :ignore-id="$question->id ?? null" />

        <div>
            <label class="block text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 border-b pb-1">Main Question / Stimulus (উদ্দীপক) <span class="text-red-500">*</span></label>
            <div wire:ignore class="rounded-md focus-within:ring-1 focus-within:ring-indigo-500 transition-all">
                <textarea id="editor">{!! $title !!}</textarea>
            </div>
            @error('title')<span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>@enderror
        </div>

        <div x-show="questionType === 'written'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="pt-4 border-t border-gray-100 dark:border-gray-700">
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1.2em" width="1.2em" xmlns="http://www.w3.org/2000/svg" class="text-indigo-500"><path d="M432 112V96a48.14 48.14 0 0 0-48-48H64a48.14 48.14 0 0 0-48 48v256a48.14 48.14 0 0 0 48 48h16v16a48.14 48.14 0 0 0 48 48h320a48.14 48.14 0 0 0 48-48V160a48.14 48.14 0 0 0-48-48zM96 128h272.24l-37.81-48.4a15.89 15.89 0 0 0-25-.63L234.62 170.81l-40.42-37.2a15.87 15.87 0 0 0-22.18 1.14l-81.82 93V96a16 16 0 0 1 16-16h320a16 16 0 0 1 16 16v224H128a48.06 48.06 0 0 0-32 12.31V144a16 16 0 0 1 0-16zM464 416a16 16 0 0 1-16 16H128a16 16 0 0 1-16-16V160a16 16 0 0 1 16-16h320a16 16 0 0 1 16 16z"></path><circle cx="336" cy="192" r="32"></circle></svg>
                Reference Image / Attachment <span class="text-xs font-normal text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full ml-1">Optional</span>
            </label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-indigo-500 transition-colors bg-gray-50 dark:bg-gray-800">
                <div class="space-y-1 text-center w-full">

                    @if ($image)
                        <div class="relative w-max mx-auto mb-4 group">
                            <img src="{{ $image->temporaryUrl() }}" class="mx-auto h-48 object-contain rounded-lg shadow-md border border-gray-200 dark:border-gray-600">
                            <button type="button" wire:click="$set('image', null)" class="absolute -top-3 -right-3 bg-red-100 hover:bg-red-500 text-red-500 hover:text-white rounded-full p-1.5 shadow-sm transition-colors opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @elseif(!empty($existingImage))
                        <div class="relative w-max mx-auto mb-4 group">
                            <img src="{{ Storage::url($existingImage) }}" class="mx-auto h-48 object-contain rounded-lg shadow-md border border-gray-200 dark:border-gray-600">
                            <button type="button" wire:click="removeExistingImage" class="absolute -top-3 -right-3 bg-red-100 hover:bg-red-500 text-red-500 hover:text-white rounded-full p-1.5 shadow-sm transition-colors opacity-0 group-hover:opacity-100" title="Remove Image">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @else
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    @endif

                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center mt-4">
                        <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-700 py-1 px-3 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 shadow-sm focus-within:outline-none transition-colors">
                            <span>Upload a file</span>
                            <input id="file-upload" wire:model="image" type="file" class="sr-only" accept="image/png, image/jpeg, image/jpg, image/webp">
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG up to 2MB</p>

                    <div wire:loading wire:target="image" class="text-xs text-indigo-500 font-bold mt-2 animate-pulse">
                        Uploading image... Please wait.
                    </div>
                </div>
            </div>
            @error('image')<span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>@enderror
        </div>

        <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700" x-show="questionType === 'mcq'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <div class="flex justify-between items-center mb-4">
                <label class="text-lg font-bold text-blue-700 dark:text-blue-400 flex items-center gap-2">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.9 177.9L218 339.8c-7.4 7.4-19.4 7.4-26.9 0l-83-83c-7.4-7.4-7.4-19.4 0-26.9l22.6-22.6c7.4-7.4 19.4-7.4 26.9 0l47 47 131-131c7.4-7.4 19.4-7.4 26.9 0l22.6 22.6c7.4 7.5 7.4 19.5 0 26.9z"></path></svg>
                    MCQ Options (বহুনির্বাচনী অপশন)
                </label>
                <button type="button" wire:click="addOption" class="flex items-center gap-1 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transition-all text-sm font-semibold">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg> Add Option
                </button>
            </div>

            <div class="grid grid-cols-1 gap-5 bg-gradient-to-br from-blue-50 to-white dark:from-gray-800 dark:to-gray-900 p-5 rounded-xl border border-blue-100 dark:border-gray-600 shadow-inner">
                @php $mcqLabels = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ']; @endphp

                @foreach($options as $i => $opt)
                    <div wire:key="opt-{{ $i }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm relative transition-all duration-200 hover:shadow-md hover:border-blue-400 group">

                        <button type="button" wire:click="removeOption({{ $i }})" class="absolute -top-3 -right-3 bg-red-100 hover:bg-red-500 text-red-500 hover:text-white rounded-full p-2 shadow-sm transition-colors opacity-0 group-hover:opacity-100">
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 352 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>
                        </button>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3 pr-4">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Option:</span>
                                <span class="w-10 h-8 flex items-center justify-center font-bold text-lg bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded shadow-sm border border-blue-200">
                                    {{ $mcqLabels[$i] ?? ($i+1) }}
                                </span>
                            </div>

                            <label class="flex items-center gap-2 sm:ml-auto cursor-pointer bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-4 py-1.5 rounded-md hover:bg-green-50 dark:hover:bg-green-900/20 hover:border-green-400 transition-colors">
                                <input type="checkbox" wire:model="options.{{ $i }}.is_correct" class="rounded text-green-500 focus:ring-green-400 h-5 w-5 border-gray-300" @if(!empty($opt['is_correct']) && $opt['is_correct']) checked @endif>
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300 select-none uppercase tracking-wide">Correct Answer</span>
                            </label>
                        </div>

                        <div wire:ignore class="rounded-md overflow-hidden border border-gray-200 dark:border-gray-600 focus-within:border-blue-500 transition-all">
                            <textarea id="opt_editor_{{ $i }}">{!! $opt['option_text'] ?? '' !!}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('options.*.option_text')<span class="text-sm text-red-500 font-bold block mt-2 text-center bg-red-50 p-2 rounded">* সবগুলো অপশন পূরণ করা আবশ্যক।</span>@enderror
        </div>

        {{-- CQ Section --}}
        <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700" x-show="questionType === 'cq'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <div class="flex justify-between items-center mb-4">
                <label class="text-lg font-bold text-purple-700 dark:text-purple-400 flex items-center gap-2">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"></path></svg>
                    Creative Questions (সৃজনশীল অংশ)
                </label>
                <button type="button" wire:click="addCqPart" class="flex items-center gap-1 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transition-all text-sm font-semibold">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg> Add Part
                </button>
            </div>

            <div class="grid gap-5 bg-gradient-to-br from-purple-50 to-white dark:from-gray-800 dark:to-gray-900 p-5 rounded-xl border border-purple-100 dark:border-gray-600 shadow-inner">
                @foreach($cq as $index => $part)
                    <div wire:key="cq-part-{{ $part['id'] ?? $index }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm relative transition-all duration-200 hover:shadow-md hover:border-purple-300 group">

                        <button type="button" wire:click="removeCqPart({{ $index }})" class="absolute -top-3 -right-3 bg-red-100 hover:bg-red-500 text-red-500 hover:text-white rounded-full p-2 shadow-sm transition-colors opacity-0 group-hover:opacity-100 z-10">
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 352 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>
                        </button>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Label:</span>
                                <input type="text" wire:model.live="cq.{{ $index }}.label" class="w-16 font-bold text-center bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 rounded border-0 focus:ring-2 focus:ring-purple-400 py-1 border border-purple-200" placeholder="ক">
                            </div>
                            <div class="flex items-center gap-2 sm:ml-auto bg-gray-50 dark:bg-gray-700 px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600">
                                <span class="text-sm font-bold text-gray-500 dark:text-gray-300">Marks:</span>
                                <input type="number" wire:model.live="cq.{{ $index }}.marks" class="w-20 rounded border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 text-center font-bold focus:ring-purple-400 py-1" min="0" step="0.5">
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase block mb-1">Question (প্রশ্ন)</span>
                            <div wire:ignore class="rounded-md overflow-hidden border border-gray-200 dark:border-gray-600 focus-within:border-purple-500 transition-all">
                                <textarea id="cq_editor_{{ $part['id'] ?? $index }}" class="cq-dynamic-editor" data-index="{{ $index }}">{!! $part['text'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div x-data="{
                                showAnswer: {{ !empty($part['answer']) ? 'true' : 'false' }},
                                initAnsEditor() {
                                    if (typeof CKEDITOR !== 'undefined' && !CKEDITOR.instances['cq_answer_{{ $part['id'] ?? $index }}']) {
                                        initCkEditor4('cq_answer_{{ $part['id'] ?? $index }}', 'cq.{{ $index }}.answer', true);
                                    }
                                }
                             }"
                             x-init="
                                if(showAnswer) setTimeout(() => initAnsEditor(), 100);
                                window.addEventListener('refresh-editors', () => {
                                    if(showAnswer) setTimeout(() => initAnsEditor(), 350);
                                });
                             "
                             class="mt-2 border-t border-gray-100 dark:border-gray-700 pt-2">

                            <button type="button"
                                    @click="showAnswer = !showAnswer; if(showAnswer) setTimeout(() => initAnsEditor(), 50);"
                                    class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 flex items-center gap-1.5 focus:outline-none transition-colors w-full text-left">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1.2em" width="1.2em" xmlns="http://www.w3.org/2000/svg" class="transition-transform duration-300" :class="showAnswer ? 'rotate-180' : ''"><path d="M256 294.1L383 167c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-144 144c-9.4 9.4-24.6 9.4-33.9 0l-144-144c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L256 294.1z"></path></svg>
                                <span x-text="showAnswer ? 'Hide Answer (উত্তর লুকান)' : 'Add Answer / Solution (উত্তর যুক্ত করুন - ঐচ্ছিক)'"></span>
                            </button>

                            <div x-show="showAnswer" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-3" style="display: none;">
                                <div wire:ignore class="rounded-md overflow-hidden border border-gray-200 dark:border-gray-600 focus-within:border-emerald-500 transition-all">
                                    <textarea id="cq_answer_{{ $part['id'] ?? $index }}">{!! $part['answer'] ?? '' !!}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" class="text-green-500"><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg>
                General Solution / Description <span class="text-xs font-normal text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full ml-1">Optional</span>
            </label>
            <div wire:ignore class="rounded-md overflow-hidden border border-gray-300 dark:border-gray-600 focus-within:border-green-500 transition-colors">
                <textarea id="description_editor">{!! $description !!}</textarea>
            </div>
        </div>

        <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-lg font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 w-full sm:w-auto flex items-center justify-center gap-2">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM224 416c-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64s64 28.654 64 64c0 35.346-28.654 64-64 64zm96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A11.996 11.996 0 0 1 320 111.48z"></path></svg>
                {{ $buttonText }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>

    <script>
        window.tsSubject = window.tsSubject || null;
        window.tsChapter = window.tsChapter || null;
        window.tsTopic = window.tsTopic || null;
        window.tsTags = window.tsTags || null;
        window.tsExamCategories = window.tsExamCategories || null;

        function generateSlug(text) {
            let div = document.createElement("div");
            div.innerHTML = text;
            let plainText = div.innerText || div.textContent || "";

            return plainText.trim()
                .toLowerCase()
                .replace(/[^\w\u0980-\u09FF\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '')
                .substring(0, 100);
        }

        function initCkEditor4(elementId, livewireProperty, isAdvanced = false) {
            const el = document.getElementById(elementId);
            if (!el || el.offsetParent === null) return;

            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[elementId]) {
                try { CKEDITOR.instances[elementId].destroy(true); } catch(e) {}
            }

            let toolbarConfig = [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Subscript', 'Superscript'] },
                { name: 'insert', items: ['SpecialCharacter', 'Mathjax'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'document', items: ['Source'] }
            ];

            if (isAdvanced) {
                toolbarConfig = [
                    { name: 'clipboard', items: ['Undo', 'Redo'] },
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialCharacter', 'Mathjax'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'tools', items: ['Maximize'] },
                    { name: 'document', items: ['Source'] }
                ];
            }

            const editor = CKEDITOR.replace(elementId, {
                extraPlugins: 'mathjax,tableresize,wordcount,notification,justify,font,colorbutton',
                mathJaxLib: '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
                toolbar: toolbarConfig,
                height: isAdvanced ? 150 : 100,
                allowedContent: true
            });

            let ckDebounceTimer;

            editor.on('change', function () {
                let data = editor.getData();

                clearTimeout(ckDebounceTimer);

                ckDebounceTimer = setTimeout(() => {
                @this.set(livewireProperty, data);

                    if (livewireProperty === 'title') {
                        let isEditMode = window.location.href.includes('/edit');
                        let slugInput = document.getElementById('slug_input');

                        if (slugInput) {
                            let isManualEdited = slugInput.getAttribute('data-manual') === 'true';

                            if (!isEditMode && !isManualEdited) {
                                let newSlug = generateSlug(data);
                                window.dispatchEvent(new CustomEvent('slug-auto-updated', { detail: newSlug }));
                            }
                        }
                    }
                }, 500);
            });
        }

        function initEditors() {
            initCkEditor4('editor', 'title');
            initCkEditor4('description_editor', 'description', true);

            document.querySelectorAll('textarea[id^="opt_editor_"]').forEach(el => {
                let idx = el.id.replace('opt_editor_', '');
                initCkEditor4(el.id, `options.${idx}.option_text`);
            });

            document.querySelectorAll('textarea.cq-dynamic-editor').forEach(el => {
                let dIndex = el.getAttribute('data-index');
                initCkEditor4(el.id, `cq.${dIndex}.text`);
            });

            if (window.tsSubject) { window.tsSubject.destroy(); window.tsSubject = null; }
            const subjectEl = document.getElementById('subject');
            if (subjectEl) window.tsSubject = new TomSelect(subjectEl, { onChange: (v) => @this.set('subject_id', v) });

            if (window.tsChapter) { window.tsChapter.destroy(); window.tsChapter = null; }
            const chapterEl = document.getElementById('chapter');
            if (chapterEl) window.tsChapter = new TomSelect(chapterEl, { onChange: (v) => @this.set('chapter_id', v) });

            if (window.tsTopic) { window.tsTopic.destroy(); window.tsTopic = null; }
            const topicEl = document.getElementById('topic');
            if (topicEl) window.tsTopic = new TomSelect(topicEl, { onChange: (v) => @this.set('topic_id', v) });

            if (window.tsTags) { window.tsTags.destroy(); window.tsTags = null; }
            const tagsEl = document.getElementById('tags');
            if (tagsEl) window.tsTags = new TomSelect(tagsEl, { plugins: ['remove_button'], persist: false, create: true, onChange: (v) => @this.set('tagIds', v) });

            // NEW: Exam Categories Initialization
            if (window.tsExamCategories) { window.tsExamCategories.destroy(); window.tsExamCategories = null; }
            const examCategoriesEl = document.getElementById('exam_categories');
            if (examCategoriesEl) window.tsExamCategories = new TomSelect(examCategoriesEl, {
                plugins: ['remove_button'],
                persist: false,
                create: false,
                onChange: (v) => @this.set('exam_category_ids', v)
            });
        }

        if (!window.hasRegisteredQuestionEvents) {

            window.addEventListener('chaptersUpdated', e => {
                if (window.tsChapter) {
                    window.tsChapter.clear(true);
                    window.tsChapter.clearOptions();
                    window.tsChapter.addOption({value: '', text: '-- Select Chapter --'});
                    window.tsChapter.addOptions(e.detail.chapters);
                    window.tsChapter.refreshOptions(false);
                }
            });

            window.addEventListener('topicsUpdated', e => {
                if (window.tsTopic) {
                    window.tsTopic.clear(true);
                    window.tsTopic.clearOptions();
                    window.tsTopic.addOption({value: '', text: '-- Select Topic --'});
                    window.tsTopic.addOptions(e.detail.topics);
                    window.tsTopic.refreshOptions(false);
                }
            });

            window.addEventListener('reset-selects', () => {
                window.tsSubject?.clear(true);
                window.tsChapter?.clear(true);
                window.tsTopic?.clear(true);
                window.tsExamCategories?.clear(true);
            });

            window.addEventListener('refresh-editors', () => setTimeout(initEditors, 350));

            document.addEventListener('livewire:load', () => setTimeout(initEditors, 100));
            document.addEventListener('livewire:navigated', () => setTimeout(initEditors, 100));
            document.addEventListener('livewire:update', () => setTimeout(initEditors, 350));

            document.addEventListener('livewire:navigating', () => {
                for (let instanceName in CKEDITOR.instances) {
                    try { CKEDITOR.instances[instanceName].destroy(true); } catch(e) {}
                }

                if (window.tsSubject) { window.tsSubject.destroy(); window.tsSubject = null; }
                if (window.tsChapter) { window.tsChapter.destroy(); window.tsChapter = null; }
                if (window.tsTopic) { window.tsTopic.destroy(); window.tsTopic = null; }
                if (window.tsTags) { window.tsTags.destroy(); window.tsTags = null; }
                if (window.tsExamCategories) { window.tsExamCategories.destroy(); window.tsExamCategories = null; }
            });

            window.hasRegisteredQuestionEvents = true;
        }
    </script>
@endpush
