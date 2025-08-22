<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search questions..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
        <a wire:navigate href="{{ route('admin.questions.create') }}"
           class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            + New Question
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Question</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Subject</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Chapter</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($questions as $q)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $q->id }}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{!! $q->title !!}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $q->subject->name }}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $q->chapter->name }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a wire:navigate href="{{ route('admin.questions.edit', $q) }}"
                           class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                        <button type="button" wire:click="deleteQuestion({{ $q->id }})"
                                onclick="confirm('Delete this question?') || event.stopImmediatePropagation()"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No questions found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $questions->links() }}</div>
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

        window.sessionSuccess = @json(session('success'));

        function handleSessionToast() {
            if (window.sessionSuccess) {
                showToast(window.sessionSuccess);
                window.sessionSuccess = null;
            }
        }

        document.addEventListener('DOMContentLoaded', handleSessionToast);
        document.addEventListener('livewire:navigated', handleSessionToast);

        window.addEventListener('questionDeleted', e => {
            showToast(e.detail.message || 'Question deleted successfully.');
        });
    </script>
@endpush

