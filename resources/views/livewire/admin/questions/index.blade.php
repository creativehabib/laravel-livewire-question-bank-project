<div>
    <div class="flex justify-between mb-4">
        <input type="text" wire:model.debounce.300ms="search"
               placeholder="Search..." class="border p-2 rounded w-1/3">
        <a wire:navigate href="{{ route('admin.questions.create') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded">+ New Question</a>
    </div>

    <table class="w-full border-collapse border">
        <thead>
        <tr class="bg-gray-100">
            <th class="border p-2">#</th>
            <th class="border p-2">Question</th>
            <th class="border p-2">Subject</th>
            <th class="border p-2">Chapter</th>
            <th class="border p-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($questions as $q)
            <tr>
                <td class="border p-2">{{ $q->id }}</td>
                <td class="border p-2">{!! $q->title !!}</td>
                <td class="border p-2">{{ $q->subject->name }}</td>
                <td class="border p-2">{{ $q->chapter->name }}</td>
                <td class="border p-2 space-x-2">
                    <a wire:navigate href="{{ route('admin.questions.edit', $q) }}"
                       class="text-blue-600 underline">Edit</a>
                    <button wire:click="delete({{ $q->id }})"
                            onclick="return confirm('Delete this question?')"
                            class="text-red-600 underline">Delete</button>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-4 text-center text-gray-500">No questions found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $questions->links() }}</div>
</div>

