<div>
    <form wire:submit.prevent="update" class="space-y-4 max-w-md">
        <div>
            <label class="block mb-1">Name</label>
            <input type="text" wire:model="name" class="border p-2 rounded w-full">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Subject</button>
    </form>
</div>
