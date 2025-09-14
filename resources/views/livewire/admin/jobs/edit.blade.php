<div class="max-w-full">
    <form wire:submit.prevent="update" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="col-span-1 lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
                <input type="text" wire:model="title" class="input-field" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
                <input type="text" wire:model="slug" class="input-field" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Summary</label>
                <textarea wire:model="summary" class="input-field"></textarea>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                <textarea wire:model="description" id="content"></textarea>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">SEO Title</label>
                <input type="text" wire:model="seo_title" class="input-field" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">SEO Description</label>
                <textarea wire:model="seo_description" class="input-field"></textarea>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">SEO Keywords</label>
                <input type="text" wire:model="seo_keywords" class="input-field" />
            </div>
        </div>
        <div class="col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
                <select wire:model="category_id" class="input-field">
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Company Name</label>
                <input type="text" wire:model="company_name" class="input-field" />
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
                    <button type="button" @click="imageUrl = null" class="px-3 py-1 bg-red-600 text-white rounded">Remove</button>
                </div>
            </div>
        </div>
        <div class="col-span-1 lg:col-span-3 text-right">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
        </div>
    </form>

    <x-media-modal />
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigated', () => {
            let imageToReplace = null;
            let savedSelection = null;
            window.selectingThumbnail = false;

            const editor = CKEDITOR.replace('content', {
                // Add extra plugins for math equations and table resizing
                extraPlugins: ['mathjax,tableresize'],

                // Set the path to the MathJax library
                mathJaxLib: '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',

                // A single, organized toolbar configuration
                toolbar: [
                    { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                    { name: 'document', items: ['Source', 'Print', '-', 'NewPage', 'Preview'] },
                    { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                    { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', 'Scayt'] },

                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'TextDirection'] },
                    { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },

                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialCharacter', 'Iframe', 'Mathjax', '-', 'ImageManager'] },

                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
                ],

                // WARNING: This disables CKEditor's content filter.
                // This allows any HTML but can be a security risk and lead to messy code.
                allowedContent: true
            });

            editor.on('change', function () {
            @this.set('description', editor.getData(), false);
            });

            // Preserve the current cursor position so images can be
            // inserted where the user expects even after the editor
            // loses focus when the media modal is opened.
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
