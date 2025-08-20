<div>
    <form wire:submit.prevent="save" class="space-y-4 max-w-md">
        <div>
            <label class="block mb-1">Subject</label>
            <select wire:model="subject_id" class="border p-2 rounded w-full">
                <option value="">-- Select --</option>
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                @endforeach
            </select>
            @error('subject_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block mb-1">Name</label>
            <input type="text" wire:model="name" class="border p-2 rounded w-full">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Chapter</button>
    </form>
</div>
