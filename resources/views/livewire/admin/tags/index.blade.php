<div>
    <div class="flex justify-between mb-4">
        <input type="text" wire:model.debounce.300ms="search" placeholder="Search..." class="border p-2 rounded w-1/3">
        <form wire:submit.prevent="save" class="flex gap-2">
            <input type="text" wire:model="name" placeholder="Tag name" class="border p-2 rounded">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add</button>
        </form>
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
        @forelse($tags as $tag)
            <tr>
                <td class="border p-2">{{ $tag->id }}</td>
                <td class="border p-2">
                    @if($editingId === $tag->id)
                        <form wire:submit.prevent="update" class="flex w-full gap-2">
                            <input type="text" wire:model="editingName" class="border p-1 rounded flex-1">
                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Save</button>
                            <button type="button" wire:click="cancelEdit" class="px-2 py-1">Cancel</button>
                        </form>
                    @else
                        {{ $tag->name }}
                    @endif
                </td>
                <td class="border p-2 space-x-2">
                    @if($editingId !== $tag->id)
                        <button wire:click="edit({{ $tag->id }})" class="text-blue-600 underline">Edit</button>
                        <button wire:click="delete({{ $tag->id }})" onclick="return confirm('Delete this tag?')" class="text-red-600 underline">Delete</button>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="p-4 text-center text-gray-500">No tags found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $tags->links() }}</div>
</div>
