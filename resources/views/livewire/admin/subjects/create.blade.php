<div>
    <form wire:submit.prevent="save" class="space-y-4 max-w-md">
        <div>
            <label class="block mb-1">Name</label>
            <input type="text" wire:model="name" class="input-field" placeholder="Enter subject name">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Subject</button>
    </form>
</div>
