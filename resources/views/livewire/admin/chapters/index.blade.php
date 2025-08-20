<div>
    <div class="flex justify-between mb-4">
        <div class="flex gap-2 w-2/3">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search..." class="border p-2 rounded flex-1">
            <select wire:model="subjectId" class="border p-2 rounded">
                <option value="">All Subjects</option>
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                @endforeach
            </select>
        </div>
        <a wire:navigate href="{{ route('admin.chapters.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ New Chapter</a>
    </div>

    <table class="w-full border-collapse border">
        <thead>
        <tr class="bg-gray-100">
            <th class="border p-2">#</th>
            <th class="border p-2 text-left">Name</th>
            <th class="border p-2">Subject</th>
            <th class="border p-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($chapters as $chapter)
            <tr>
                <td class="border p-2">{{ $chapter->id }}</td>
                <td class="border p-2">{{ $chapter->name }}</td>
                <td class="border p-2">{{ $chapter->subject->name }}</td>
                <td class="border p-2 space-x-2">
                    <a wire:navigate href="{{ route('admin.chapters.edit', $chapter) }}" class="text-blue-600 underline">Edit</a>
                    <button wire:click="delete({{ $chapter->id }})" onclick="return confirm('Delete this chapter?')" class="text-red-600 underline">Delete</button>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="p-4 text-center text-gray-500">No chapters found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $chapters->links() }}</div>
</div>
