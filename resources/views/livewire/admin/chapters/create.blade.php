<div>
    <form wire:submit.prevent="save" class="space-y-4 max-w-md">
        <div wire:ignore>
            <label class="block mb-1">Subject</label>
            <select id="subject" class="border p-2 rounded w-full">
                <option value="">-- Select --</option>
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" @selected($sub->id == $subject_id)>{{ $sub->name }}</option>
                @endforeach
            </select>
            @error('subject_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div wire:ignore>
            <label class="block mb-1">Sub Subject</label>
            <select id="sub_subject" class="border p-2 rounded w-full">
                <option value="">-- Select --</option>
                @foreach($subSubjects as $subSubject)
                    <option value="{{ $subSubject->id }}" @selected($subSubject->id == $sub_subject_id)>{{ $subSubject->name }}</option>
                @endforeach
            </select>
            @error('sub_subject_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block mb-1">Name</label>
            <input type="text" wire:model="name" class="border p-2 rounded w-full">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Chapter</button>
    </form>
</div>

@push('scripts')
    <script>
        let tsSubject, tsSubSubject;

        function initSelects() {
            if (tsSubject) tsSubject.destroy();
            tsSubject = new TomSelect('#subject', {
                onChange: value => @this.set('subject_id', value)
            });
            tsSubject.setValue(@json($subject_id), true);

            if (tsSubSubject) tsSubSubject.destroy();
            tsSubSubject = new TomSelect('#sub_subject', {
                onChange: value => @this.set('sub_subject_id', value)
            });
            tsSubSubject.setValue(@json($sub_subject_id), true);
        }

        window.addEventListener('subSubjectsUpdated', e => {
            if (!tsSubSubject) return;
            tsSubSubject.clearOptions();
            tsSubSubject.addOptions(e.detail.subSubjects);
            tsSubSubject.refreshOptions(false);
            tsSubSubject.setValue('');
        });

        document.addEventListener('livewire:load', initSelects);
        document.addEventListener('livewire:navigated', initSelects);
    </script>
@endpush
