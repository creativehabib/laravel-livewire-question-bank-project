<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
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
            <label class="block mb-1 text-sm font-medium">Category ID</label>
            <input type="number" wire:model="category_id" class="w-full px-3 py-2 border rounded" />
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
            <textarea wire:model="description" class="w-full px-3 py-2 border rounded"></textarea>
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
</div>
