<div>
    <form wire:submit.prevent="save" class="mb-4 flex gap-2">
        <input type="text" wire:model="name" placeholder="Tag name" class="border p-2 rounded flex-1">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add</button>
    </form>

    <ul class="space-y-2">
        @foreach($tags as $tag)
            <li class="flex justify-between items-center border p-2 rounded">
                @if($editingId === $tag->id)
                    <form wire:submit.prevent="update" class="flex w-full gap-2">
                        <input type="text" wire:model="editingName" class="border p-1 rounded flex-1">
                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Save</button>
                        <button type="button" wire:click="cancelEdit" class="px-2 py-1">Cancel</button>
                    </form>
                @else
                    <span>{{ $tag->name }}</span>
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $tag->id }})" class="text-blue-500">Edit</button>
                        <button wire:click="delete({{ $tag->id }})" class="text-red-500">Delete</button>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</div>
