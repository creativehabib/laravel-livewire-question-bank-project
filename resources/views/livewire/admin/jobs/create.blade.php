<div class="max-w-7xl mx-auto">
    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
                    <input type="text" wire:model="title" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
                    <input type="text" wire:model="slug" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Summary</label>
                    <textarea wire:model="summary" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                    <div wire:ignore>
                        <textarea wire:model="description" id="content" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                    </div>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="featured" id="featured" class="mr-2" />
                    <label for="featured" class="text-sm text-gray-700 dark:text-gray-200">Is Featured?</label>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-200">SEO Settings</h2>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Meta Title</label>
                    <input type="text" wire:model="seo_title" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Meta Description</label>
                    <textarea wire:model="seo_description" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Meta Keywords</label>
                    <input type="text" wire:model="seo_keywords" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                    <select wire:model="status" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @foreach(\App\Enums\JobStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
                    <select wire:model="category_id" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Company Name</label>
                    <input type="text" wire:model="company_name" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Deadline</label>
                    <input type="date" wire:model="deadline" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Posted At</label>
                    <input type="datetime-local" wire:model="posted_at" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <div x-data="{ imageUrl: @entangle('cover_image') }">
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Featured Image</label>
                    <div x-show="!imageUrl" @click="window.selectingThumbnail = true; window.dispatchEvent(new CustomEvent('open-media-modal'))" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-10 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                        <p class="text-gray-500 dark:text-gray-400">Select Image</p>
                    </div>
                    <div x-show="imageUrl" class="space-y-2">
                        <img :src="imageUrl" class="h-32 w-32 object-cover rounded" />
                        <button type="button" @click="imageUrl = null" class="px-3 py-1 bg-red-600 text-white rounded">Remove</button>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                </div>
            </div>
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
                toolbar: [
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Blockquote'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['ImageManager'] },
                    { name: 'tools', items: ['Source'] }
                ],
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
