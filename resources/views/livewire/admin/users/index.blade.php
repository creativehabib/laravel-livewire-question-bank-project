<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Email</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Role</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->id }}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->name }}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                    <td class="px-4 py-2">
                        <select class="px-2 py-1 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" wire:change="changeRole({{ $user->id }}, $event.target.value)">
                            @foreach($roles as $roleOption)
                                <option value="{{ $roleOption->value }}" @selected($user->role === $roleOption)>{{ ucfirst($roleOption->value) }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No users found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
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

    window.addEventListener('roleUpdated', e => {
        showToast(e.detail.message || 'Role updated successfully.');
    });
</script>
@endpush
