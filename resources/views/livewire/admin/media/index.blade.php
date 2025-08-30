<div x-data="mediaManager">
    <x-slot name="title">Media Library</x-slot>

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Media Library</h1>
        <button @click="isUploaderOpen = true" type="button" class="flex items-center">
            <x-heroicon-o-arrow-up-tray class="w-5 h-5 mr-2"/>
            Add Media
        </button>
    </div>

    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-4 lg:grid-cols-9 gap-4 mt-6">
        @forelse ($mediaItems as $item)
            <div
                wire:click="selectMedia({{ $item->id }})"
                class="relative group aspect-square cursor-pointer">
                <img src="{{ $item->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                    <span class="text-white font-semibold">View Details</span>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No media found.</p>
            </div>
        @endforelse
    </div>

    @if ($mediaItems->hasPages())
        <div class="mt-6">{{ $mediaItems->links() }}</div>
    @endif

    <div
        x-show="isDetailsDrawerOpen"
        @open-details-drawer.window="isDetailsDrawerOpen = true; isUploaderOpen = false"
        x-transition:enter="transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-full sm:w-96 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 shadow-lg z-50"
        x-cloak>

        <div class="flex flex-col h-full">
            @if($selectedMedia)
                <div class="p-4 border-b dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">File Details</h3>
                        <button @click="isDetailsDrawerOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <x-heroicon-o-x-mark class="w-6 h-6"/>
                        </button>
                    </div>
                </div>

                <div class="flex-1 p-4 overflow-y-auto space-y-6">
                    <div class="p-2 border rounded-lg dark:border-gray-700">
                        <img src="{{ $selectedMedia->url }}" alt="{{ $selectedMedia->name }}" class="w-full h-auto object-contain rounded-md max-h-60">
                    </div>

                    <div x-data="{ isUploading: false, progress: 0 }"
                         x-on:livewire-upload-start="isUploading = true"
                         x-on:livewire-upload-finish="isUploading = false"
                         x-on:livewire-upload-error="isUploading = false"
                         x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <label for="replace-file" class="flex items-center w-full text-center cursor-pointer">
                            <x-heroicon-o-arrow-path class="w-5 h-5 mr-2"/> <span>Replace Image</span>
                        </label>
                        <input id="replace-file" wire:model="newFile" type="file" class="sr-only">
                        <div x-show="isUploading" class="w-full bg-gray-200 rounded-full mt-2">
                            <div class="bg-indigo-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" :style="`width: ${progress}%`" x-text="`${progress}%`"></div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="media-name" class="label">Name</label>
                            <input type="text" id="media-name" wire:model.defer="newName" class="input-form w-full">
                        </div>
                        <div>
                            <label class="label">URL</label>
                            <div class="flex">
                                <input type="text" readonly value="{{ $selectedMedia->url }}" id="media-url-display" class="input-form flex-1 rounded-r-none bg-gray-100 dark:bg-gray-900">
                                <button @click="copyToClipboard($el)" data-url="{{ $selectedMedia->url }}" class="btn btn-secondary rounded-l-none w-24">Copy</button>
                            </div>
                        </div>
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Dimensions</dt>
                            <dd class="text-gray-800 dark:text-gray-200">{{ $selectedMedia->width ?? 'N/A' }} x {{ $selectedMedia->height ?? 'N/A' }}</dd>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Size</dt>
                            <dd class="text-gray-800 dark:text-gray-200">{{ number_format($selectedMedia->size / 1024, 2) }} KB</dd>
                        </dl>
                    </div>
                </div>

                <div class="p-4 border-t dark:border-gray-700 flex justify-between items-center">
                    <button
                        @click="$dispatch('confirm-delete', { id: {{ $selectedMedia->id }} })"
                        class="btn btn-danger">
                        Delete Permanently
                    </button>
                    <button wire:click="updateMediaName" wire:loading.attr="disabled" class="btn btn-primary">
                        <span wire:loading.remove wire:target="updateMediaName">Save Changes</span>
                        <span wire:loading wire:target="updateMediaName">Saving...</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div x-show="isDetailsDrawerOpen" @click="isDetailsDrawerOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40" x-cloak></div>

    <div
        x-show="isUploaderOpen"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-cloak>
        <div
            @click.outside="isUploaderOpen = false"
            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-2xl"
            x-data="{ activeTab: 'upload' }">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Media Uploader</h3>
                <button @click="isUploaderOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <x-heroicon-o-x-mark class="w-6 h-6"/>
                </button>
            </div>

            <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                <nav class="flex space-x-4" aria-label="Tabs">
                    <button @click="activeTab = 'upload'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'upload', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'upload' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                        Upload Media
                    </button>
                    <button @click="activeTab = 'url'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'url', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'url' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                        Upload From URL
                    </button>
                </nav>
            </div>

            <div x-show="activeTab === 'upload'">
                <div x-data="{ isUploading: false, progress: 0 }"
                     x-on:livewire-upload-start="isUploading = true"
                     x-on:livewire-upload-finish="isUploading = false; isUploaderOpen = false;"
                     x-on:livewire-upload-error="isUploading = false"
                     x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <label for="file-upload" class="relative cursor-pointer border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-10 text-center block hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="text-center">
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Drop files to upload</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">or</p>
                            <span class="mt-2 btn btn-dark">Select Files</span>
                        </div>
                        <input id="file-upload" wire:model="file" type="file" class="sr-only">
                    </label>

                    <div x-show="isUploading" class="w-full bg-gray-200 rounded-full mt-4">
                        <div class="bg-indigo-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" :style="`width: ${progress}%`" x-text="`${progress}%`"></div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'url'">
                <div class="flex">
                    <input type="text" wire:model.defer="url" id="media-url-input" placeholder="https://example.com/image.jpg" class="flex-1 input-form" />
                    <button wire:click.prevent="uploadFromUrl" wire:loading.attr="disabled" class="btn btn-primary rounded-l-none">
                        <span wire:loading.remove wire:target="uploadFromUrl">Upload</span>
                        <span wire:loading wire:target="uploadFromUrl">Uploading...</span>
                    </button>
                </div>
                @error('url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

