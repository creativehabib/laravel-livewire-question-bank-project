<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-4">
        <div class="flex-1 flex flex-col sm:flex-row gap-2">
            <input type="file" wire:model="file" class="flex-1" />
            <input type="text" wire:model="name" placeholder="Name" class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
            <button wire:click="upload" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Upload</button>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Mime</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Size</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($mediaItems as $media)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $media->id }}</td>
                    <td class="px-4 py-2">
                        @if($editingId === $media->id)
                            <form wire:submit.prevent="update" class="flex w-full gap-2">
                                <input type="text" wire:model="editingName" class="flex-1 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
                                <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                                <button type="button" wire:click="cancelEdit" class="px-3 py-1 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">Cancel</button>
                            </form>
                        @else
                            <span class="text-gray-700 dark:text-gray-300">{{ $media->name }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $media->mime_type }}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ number_format($media->size / 1024, 2) }} KB</td>
                    <td class="px-4 py-2 space-x-2">
                        @if($replacingId === $media->id)
                            <form wire:submit.prevent="replace" class="flex gap-2">
                                <input type="file" wire:model="replaceFile" class="flex-1" />
                                <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                                <button type="button" wire:click="cancelReplace" class="px-3 py-1 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">Cancel</button>
                            </form>
                        @else
                            <button wire:click="edit({{ $media->id }})" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</button>
                            <button wire:click="startReplace({{ $media->id }})" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">Replace</button>
                            <button type="button" onclick="confirmDelete({{ $media->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No media found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $mediaItems->links() }}</div>
</div>

@push('scripts')
<script>
    function showToast(message) {
        if (!window.Swal) return;
        Swal.fire({
            toast: true,
            icon: 'success',
            title: message,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
        });
    }

    function confirmDelete(id) {
        if (!window.Swal) return;
        Swal.fire({
            title: 'Delete this media?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteMediaConfirmed', { id: id });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('mediaUploaded', e => showToast(e.detail.message));
        window.addEventListener('mediaUpdated', e => showToast(e.detail.message));
        window.addEventListener('mediaReplaced', e => showToast(e.detail.message));
        window.addEventListener('mediaDeleted', e => showToast(e.detail.message));
    });
</script>
@endpush
