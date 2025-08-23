@props(['id' => 'media-library-modal'])

<div x-show="show"
     x-data="{
        show: false,
        activeTab: 'library',
        images: [],
        selectedImage: null,
        loading: true,
        init() {
            // Watch for the 'show' property change
            this.$watch('show', value => {
                if (value) {
                    this.loadImages();
                    document.body.classList.add('overflow-y-hidden'); // Prevent background scroll
                } else {
                    document.body.classList.remove('overflow-y-hidden');
                }
            });
        },
        // Fetch all images from the server
        loadImages() {
            this.loading = true;
            fetch('{{ route('admin.media.all') }}')
                .then(response => response.json())
                .then(data => {
                    this.images = data;
                    this.loading = false;
                }).catch(err => {
                    console.error('Failed to load media:', err);
                    this.loading = false;
                });
        },
        // Set the selected image
        selectImage(image) {
            this.selectedImage = image;
        },
        // Dispatch the selected image URL to the editor
        insertImage() {
            if (this.selectedImage) {
                window.dispatchEvent(new CustomEvent('image-selected', { detail: { url: this.selectedImage.url } }));
                this.show = false;
                this.selectedImage = null;
            }
        }
     }"
     x-on:open-media-modal.window="show = true"
     x-transition
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
     x-cloak>

    <div @click.outside="show = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl h-[90vh] flex flex-col">
        <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Select Image</h3>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <x-heroicon-o-x-mark class="w-6 h-6"/>
            </button>
        </div>

        <div class="p-4 border-b dark:border-gray-700">
            <nav class="flex space-x-4" aria-label="Tabs">
                <button @click="activeTab = 'library'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'library' }" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">Media Library</button>
                <button @click="activeTab = 'upload'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'upload' }" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">Upload Media</button>
                <button @click="activeTab = 'url'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'url' }" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">Upload Form URL</button>
            </nav>
        </div>

        <div class="flex-1 p-4 overflow-y-auto">
            <div x-show="activeTab === 'library'">
                <div x-show="loading" class="text-center text-gray-500 dark:text-gray-400">Loading media...</div>
                <div x-show="!loading" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-4">
                    <template x-for="image in images" :key="image.id">
                        <div @click="selectImage(image)" class="relative group aspect-square cursor-pointer">
                            <img :src="image.url" :alt="image.name" class="w-full h-full object-cover rounded-lg">
                            <div class="absolute inset-0 rounded-lg transition-all" :class="{ 'ring-4 ring-indigo-500 ring-inset': selectedImage && selectedImage.id === image.id }"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="p-4 border-t dark:border-gray-700 flex justify-end">
            <button @click="show = false" type="button" class="btn btn-secondary mr-2">Cancel</button>
            <button @click="insertImage()" :disabled="!selectedImage" type="button" class="btn btn-primary">Insert</button>
        </div>
    </div>
</div>
