<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block mb-1 text-sm font-medium">Name</label>
            <input type="text" wire:model="name" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Slug</label>
            <input type="text" wire:model="slug" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Details</label>
            <textarea wire:model="details" class="w-full px-3 py-2 border rounded"></textarea>
        </div>
        <div x-data="{ logoUrl: @entangle('logo') }">
            <label class="block mb-1 text-sm font-medium">Logo</label>
            <div x-show="!logoUrl" @click="window.dispatchEvent(new CustomEvent('open-media-modal'))" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-10 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                <p class="text-gray-500 dark:text-gray-400">Select Logo</p>
            </div>
            <div x-show="logoUrl" class="space-y-2">
                <img :src="logoUrl" class="h-32 w-32 object-cover rounded" />
                <button type="button" @click="logoUrl = null" class="px-3 py-1 bg-red-600 text-white rounded">Remove</button>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
        </div>
    </form>
    <x-media-modal />
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', () => {
        const handler = e => {
            @this.set('logo', e.detail.url);
        };
        window.removeEventListener('image-selected', handler);
        window.addEventListener('image-selected', handler);
    });
</script>
@endpush
