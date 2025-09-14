<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search jobs..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
        <a wire:navigate href="{{ route('admin.jobs.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">New Job</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Title</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Company</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Status</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($jobs as $job)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $job->id }}</td>
                        <td class="px-4 py-2">{!! $job->description !!} </td>
                        <td class="px-4 py-2">{{ $job->company?->name }}</td>
                        <td class="px-4 py-2">{{ ucfirst($job->status->value) }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a wire:navigate href="{{ route('admin.jobs.edit', $job) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                            <button type="button" onclick="confirmDelete({{ $job->id }})" class="text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No jobs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $jobs->links() }}</div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (!window.Swal) return;
        Swal.fire({
            title: 'Delete this job?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteJobConfirmed', { id: id });
            }
        });
    }

    window.addEventListener('jobDeleted', e => {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                icon: 'success',
                title: e.detail.message || 'Job deleted successfully.',
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });
</script>
@endpush
