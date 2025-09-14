@props(['submitAction' => 'save', 'buttonText' => 'Save'])

<div class="max-w-full">
    {{-- আমরা ডাইনামিকভাবে submitAction ব্যবহার করছি --}}
    <form wire:submit.prevent="{{ $submitAction }}" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- বাম কলামের কন্টেন্ট --}}
        <div class="col-span-1 lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
                <input type="text" wire:model="title" class="input-field" />
                @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
                <input type="text" wire:model="slug" class="input-field" />
                @error('slug') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Summary</label>
                <textarea wire:model="summary" class="input-field"></textarea>
                @error('summary') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                <div wire:ignore>
                    {{-- CKEditor এর জন্য id ঠিক রাখতে হবে --}}
                    <textarea wire:model="description" id="content" class="input-field"></textarea>
                    @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            {{-- বাকি SEO ফিল্ডগুলো এখানে থাকবে --}}
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">SEO Title</label>
                <input type="text" wire:model="seo_title" class="input-field" />
                @error('seo_title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">SEO Description</label>
                <textarea wire:model="seo_description" class="input-field"></textarea>
                @error('seo_description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">SEO Keywords</label>
                <input type="text" wire:model="seo_keywords" class="input-field" />
                @error('seo_keywords') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- ডান কলামের কন্টেন্ট --}}
        <div class="col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
                {{-- Livewire component-এর ভ্যারিয়েবল এখানে কাজ করবে --}}
                <select wire:model="category_id" class="input-field">
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Company</label>
                <select wire:model="company_id" class="input-field">
                    <option value="">Select company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Deadline</label>
                    <input type="date" wire:model="deadline" class="input-field" />
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Posted At</label>
                    <input type="datetime-local" wire:model="posted_at" class="input-field" />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                    <select wire:model="status" class="input-field">
                        @foreach(\App\Enums\JobStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center md:mt-6">
                    <input type="checkbox" wire:model="featured" id="featured" class="mr-2" />
                    <label for="featured" class="text-sm text-gray-700 dark:text-gray-200">Featured</label>
                </div>
            </div>
            <div x-data="{ imageUrl: @entangle('cover_image') }">
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Thumbnail</label>
                <div x-show="!imageUrl" @click="window.selectingThumbnail = true; window.dispatchEvent(new CustomEvent('open-media-modal'))" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-10 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                    <p class="text-gray-500 dark:text-gray-400">Select Thumbnail</p>
                </div>
                <div x-show="imageUrl" class="space-y-2">
                    <img :src="imageUrl" class="h-32 w-32 object-cover rounded" />
                    <button type="button" @click="imageUrl = null; @this.set('cover_image', null)" class="px-3 py-1 bg-red-600 text-white rounded">Remove</button>
                </div>
            </div>
        </div>

        {{-- সাবমিট বাটন --}}
        <div class="col-span-1 lg:col-span-3 text-right">
            {{-- আমরা ডাইনামিকভাবে buttonText ব্যবহার করছি --}}
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">{{ $buttonText }}</button>
        </div>
    </form>

    {{-- মিডিয়া মডাল অপরিবর্তিত থাকবে --}}
    <x-media-modal />
</div>

@push('scripts')
    {{-- ডুপ্লিকেট হওয়া সম্পূর্ণ স্ক্রিপ্টটি এখানে রাখুন --}}
    <script>
        document.addEventListener('livewire:navigated', () => {
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.content) {
                CKEDITOR.instances.content.destroy(true);
            }

            let imageToReplace = null;
            let savedSelection = null;
            window.selectingThumbnail = false;

            const editor = CKEDITOR.replace('content', {
                extraPlugins: 'mathjax,tableresize,wordcount,notification',
                mathJaxLib: '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
                toolbar: [
                    { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                    { name: 'document', items: ['Source', '-', 'Preview'] },
                    { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                    { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialCharacter', 'Mathjax', '-', 'ImageManager'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'tools', items: ['Maximize'] }
                ],
                allowedContent: true
            });

            editor.on('change', function () {
            @this.set('description', editor.getData(), false);
            });

            // বাকি জাভাস্ক্রিপ্ট কোড...
            editor.on('selectionChange', function () {
                const selection = editor.getSelection();
                const ranges = selection ? selection.getRanges() : [];
                if (ranges.length) {
                    savedSelection = ranges[0].clone();
                }
            });

            editor.addCommand('openMediaModal', {
                exec: function () {
                    imageToReplace = null;
                    window.dispatchEvent(new CustomEvent('open-media-modal'));
                }
            });

            editor.ui.addButton('ImageManager', {
                label: 'Image',
                command: 'openMediaModal',
                toolbar: 'insert',
                icon: 'image'
            });

            editor.on('doubleclick', function (evt) {
                const element = evt.data.element;
                if (element && element.is('img')) {
                    imageToReplace = element;
                    window.dispatchEvent(new CustomEvent('open-media-modal'));
                }
            });

            const imageSelectedHandler = (event) => {
                if (window.selectingThumbnail) {
                @this.set('cover_image', event.detail.url);
                    window.selectingThumbnail = false;
                } else {
                    const url = event.detail.url;
                    if (imageToReplace) {
                        imageToReplace.setAttribute('src', url);
                    } else {
                        editor.focus();
                        if (savedSelection) {
                            editor.getSelection().selectRanges([savedSelection]);
                        }
                        editor.insertHtml('<img src="' + url + '" alt="" />');
                    }
                @this.set('description', editor.getData(), false);
                    imageToReplace = null;
                    savedSelection = null;
                }
            };

            window.removeEventListener('image-selected', imageSelectedHandler);
            window.addEventListener('image-selected', imageSelectedHandler);
        });
    </script>
@endpush
