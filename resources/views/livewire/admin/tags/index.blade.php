<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tags..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
        <form wire:submit.prevent="save" class="flex gap-2">
            <input type="text" wire:model="name" placeholder="Tag name"
                   class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Add</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($tags as $tag)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $tag->id }}</td>
                    <td class="px-4 py-2">
                        @if($editingId === $tag->id)
                            <form wire:submit.prevent="update" class="flex w-full gap-2">
                                <input type="text" wire:model="editingName"
                                       class="flex-1 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
                                <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                                <button type="button" wire:click="cancelEdit"
                                        class="px-3 py-1 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">Cancel</button>
                            </form>
                        @else
                            <span class="text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        @if($editingId !== $tag->id)
                            <button wire:click="edit({{ $tag->id }})"
                                    class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</button>
                            <button type="button" onclick="confirmDelete({{ $tag->id }})"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No tags found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tags->links() }}</div>
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
            title: 'Delete this tag?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteTagConfirmed', { id: id });
            }
        });
    }

    window.sessionSuccess = @json(session('success'));

    function handleSessionToast() {
        if (window.sessionSuccess) {
            showToast(window.sessionSuccess);
            window.sessionSuccess = null;
        }
    }

    document.addEventListener('DOMContentLoaded', handleSessionToast);
    document.addEventListener('livewire:navigated', handleSessionToast);

    window.addEventListener('tagSaved', e => {
        showToast(e.detail.message || 'Tag added successfully.');
    });

    window.addEventListener('tagUpdated', e => {
        showToast(e.detail.message || 'Tag updated successfully.');
    });

    window.addEventListener('tagDeleted', e => {
        showToast(e.detail.message || 'Tag deleted successfully.');
    });
</script>
@endpush
