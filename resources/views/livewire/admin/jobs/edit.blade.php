<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    <form wire:submit.prevent="update" class="space-y-4">
        <div>
            <label class="block mb-1 text-sm font-medium">Title</label>
            <input type="text" wire:model="title" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Slug</label>
            <input type="text" wire:model="slug" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Category</label>
            <select wire:model="category_id" class="w-full px-3 py-2 border rounded">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Company Name</label>
            <input type="text" wire:model="company_name" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Summary</label>
            <textarea wire:model="summary" class="w-full px-3 py-2 border rounded"></textarea>
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Description</label>
            <div wire:ignore>
                <div id="description-editor" class="w-full px-3 py-2 border rounded"></div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Deadline</label>
                <input type="date" wire:model="deadline" class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Posted At</label>
                <input type="datetime-local" wire:model="posted_at" class="w-full px-3 py-2 border rounded" />
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Status</label>
                <select wire:model="status" class="w-full px-3 py-2 border rounded">
                    @foreach(\App\Enums\JobStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center mt-6">
                <input type="checkbox" wire:model="featured" id="featured" class="mr-2" />
                <label for="featured" class="text-sm">Featured</label>
            </div>
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Cover Image</label>
            <input type="text" wire:model="cover_image" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">SEO Title</label>
            <input type="text" wire:model="seo_title" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">SEO Description</label>
            <textarea wire:model="seo_description" class="w-full px-3 py-2 border rounded"></textarea>
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">SEO Keywords</label>
            <input type="text" wire:model="seo_keywords" class="w-full px-3 py-2 border rounded" />
        </div>
        <div class="text-right">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
        </div>
    </form>

    {{-- Media Modal Component (অপরিবর্তিত) --}}
    <x-media-modal />
</div>


@push('scripts')
<script>
    document.addEventListener('livewire:navigated', () => {
        let quillInstance = null;
        let imageToReplace = null; // রিপ্লেস করার জন্য ইমেজ নোড সংরক্ষণ করবে

        // থাম্বনেইলের জন্য মিডিয়া মডাল খোলার ফাংশন
        function showThumbnailOptions() {
            window.dispatchEvent(new CustomEvent('open-media-modal', {
                detail: { context: 'thumbnail' }
            }));
        }

        function initializeQuill() {
            const editorEl = document.getElementById('description-editor');
            if (editorEl && !editorEl.__quill) {
                const toolbarOptions = [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image', 'video'],
                    ['clean']
                ];

                quillInstance = new Quill(editorEl, {
                    theme: 'snow',
                    modules: { toolbar: toolbarOptions }
                });

                quillInstance.root.innerHTML = @js($description ?? '');

                quillInstance.on('text-change', () => {
                    @this.set('description', quillInstance.root.innerHTML, false);
                });

                // টুলবারের ইমেজ আইকন হ্যান্ডলার (নতুন ইমেজ যুক্ত করার জন্য)
                quillInstance.getModule('toolbar').addHandler('image', () => {
                    imageToReplace = null; // নতুন ইমেজ, তাই কোনো কিছু রিপ্লেস হবে না
                    showImageOptionsDialog();
                });

                // এডিটরের ভেতরের কোনো ইমেজে ক্লিক করলে রিপ্লেসের অপশন দেখাবে
                quillInstance.root.addEventListener('click', (e) => {
                    if (e.target && e.target.tagName === 'IMG') {
                        imageToReplace = e.target; // রিপ্লেস করার জন্য ইমেজটি সংরক্ষণ করা হলো
                        showImageOptionsDialog();
                    }
                });

                editorEl.__quill = quillInstance;
            }
        }

        // **** মূল সমাধানটি এখানে ****
        // কাস্টম ডায়ালগ বক্স দেখানোর ফাংশন
        function showImageOptionsDialog() {
            const dialogTitle = imageToReplace ? 'Replace Image' : 'Add Image';

            Swal.fire({
                title: dialogTitle,
                text: 'How do you want to add the image?',
                showDenyButton: true,
                confirmButtonText: `From Media Library`,
                denyButtonText: `From URL`,
                confirmButtonColor: '#4f46e5',
                denyButtonColor: '#6b7280',
            }).then((result) => {
                if (result.isConfirmed) {
                    // "Media Library" বাটনে ক্লিক করলে
                    window.dispatchEvent(new CustomEvent('open-media-modal'));
                } else if (result.isDenied) {
                    // "From URL" বাটনে ক্লিক করলে
                    Swal.fire({
                        title: 'Enter Image URL',
                        input: 'text',
                        // যদি ইমেজ রিপ্লেস করা হয়, তাহলে পুরোনো URL টি দেখানো হবে
                        inputValue: imageToReplace ? imageToReplace.src : '',
                        inputPlaceholder: 'https://example.com/image.jpg',
                        showCancelButton: true,
                        confirmButtonText: imageToReplace ? 'Replace' : 'Insert',
                        inputValidator: (url) => {
                            if (!url) {
                                return 'You need to provide a URL!'
                            }
                        }
                    }).then((urlResult) => {
                        if (urlResult.isConfirmed && urlResult.value) {
                            insertOrReplaceImage(urlResult.value);
                        }
                    });
                }
            });
        }

        // মিডিয়া মডাল বা URL ডায়ালগ থেকে পাওয়া URL দিয়ে ইমেজ যুক্ত বা রিপ্লেস করার ফাংশন
        function insertOrReplaceImage(url) {
            if (!quillInstance) return;

            if (imageToReplace) {
                // যদি কোনো ইমেজ রিপ্লেস করার জন্য সিলেক্ট করা থাকে
                imageToReplace.setAttribute('src', url);
            } else {
                // নতুন ইমেজ যুক্ত করা হচ্ছে
                const range = quillInstance.getSelection(true);
                quillInstance.insertEmbed(range.index, 'image', url, 'user');
            }

            // Livewire-কে আপডেট করা
        @this.set('description', quillInstance.root.innerHTML, false);
            imageToReplace = null; // কাজ শেষে রিসেট
        }

        // মিডিয়া মডাল থেকে ইমেজ সিলেক্ট করার পর এই ইভেন্টটি কাজ করবে
        const imageSelectedHandler = (event) => {
            insertOrReplaceImage(event.detail.url);
        };

        initializeQuill();

        // ইভেন্ট লিসেনারটি পেজ লোডের সময় একবারই যুক্ত হবে
        window.removeEventListener('image-selected', imageSelectedHandler);
        window.addEventListener('image-selected', imageSelectedHandler);
    });
</script>
@endpush
