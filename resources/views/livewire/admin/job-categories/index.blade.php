<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search categories..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
        <a wire:navigate href="{{ route('admin.job-categories.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Add Category</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Image</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Slug</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $category->id }}</td>
                    <td class="px-4 py-2">
                        @if($category->image)
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="h-10 w-10 object-cover rounded">
                        @endif
                    </td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $category->name }}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $category->slug }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a wire:navigate href="{{ route('admin.job-categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                        <button type="button" onclick="confirmDelete({{ $category->id }})" class="text-red-600 hover:text-red-800">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No categories found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
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
            title: 'Delete this category?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteCategoryConfirmed', { id: id });
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

    window.addEventListener('categoryDeleted', e => {
        showToast(e.detail.message || 'Category deleted successfully.');
    });
</script>
@endpush
