<div>
    <div class="flex justify-between mb-4">
        <input type="text" wire:model.debounce.300ms="search" placeholder="Search..." class="border p-2 rounded w-1/3">
        <a wire:navigate href="{{ route('admin.subjects.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ New Subject</a>
    </div>

    <table class="w-full border-collapse border">
        <thead>
        <tr class="bg-gray-100">
            <th class="border p-2">#</th>
            <th class="border p-2 text-left">Name</th>
            <th class="border p-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($subjects as $subject)
            <tr>
                <td class="border p-2">{{ $subject->id }}</td>
                <td class="border p-2">{{ $subject->name }}</td>
                <td class="border p-2 space-x-2">
                    <a wire:navigate href="{{ route('admin.subjects.edit', $subject) }}" class="text-blue-600 underline">Edit</a>
                    <button wire:click="delete({{ $subject->id }})" onclick="return confirm('Delete this subject?')" class="text-red-600 underline">Delete</button>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="p-4 text-center text-gray-500">No subjects found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $subjects->links() }}</div>
</div>
