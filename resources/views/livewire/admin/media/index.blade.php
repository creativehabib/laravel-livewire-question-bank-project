@push('styles')
<link rel="stylesheet" href="https://unpkg.com/dropzone@6.0.0/dist/dropzone.css" />
@endpush

<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="mb-4 flex justify-end">
            <button id="open-uploader" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Add Media</button>
        </div>
        <div class="mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Mime</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Size</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($mediaItems as $media)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $media->id }}</td>
                        <td class="px-4 py-2">
                            @if($editingId === $media->id)
                                <form wire:submit.prevent="update" class="flex w-full gap-2">
                                    <input type="text" wire:model="editingName" class="flex-1 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
                                    <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                                    <button type="button" wire:click="cancelEdit" class="px-3 py-1 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">Cancel</button>
                                </form>
                            @else
                                @php
                                    $details = [
                                        'name' => $media->name,
                                        'mime' => $media->mime_type,
                                        'size' => $media->size,
                                        'width' => $media->width,
                                        'height' => $media->height,
                                        'url' => Storage::url($media->path),
                                    ];
                                @endphp
                                <button type="button" onclick='showDetails(@json($details))' class="text-indigo-600 hover:underline dark:text-indigo-400">{{ $media->name }}</button>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $media->mime_type }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ number_format($media->size / 1024, 2) }} KB</td>
                        <td class="px-4 py-2 space-x-2">
                            @if($replacingId === $media->id)
                                <form wire:submit.prevent="replace" class="flex gap-2">
                                    <input type="file" wire:model="replaceFile" class="flex-1" />
                                    <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                                    <button type="button" wire:click="cancelReplace" class="px-3 py-1 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">Cancel</button>
                                </form>
                            @else
                                <button wire:click="edit({{ $media->id }})" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</button>
                                <button wire:click="startReplace({{ $media->id }})" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">Replace</button>
                                <button type="button" onclick="confirmDelete({{ $media->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No media found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $mediaItems->links() }}</div>
    </div>

    <div id="upload-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-md w-full max-w-lg">
            <div class="mb-4">
                <form action="{{ route('admin.media.upload') }}" id="media-dropzone" class="dropzone border-2 border-dashed rounded-md"></form>
            </div>
            <div class="flex mb-4">
                <input type="text" id="media-url" placeholder="Media URL" class="flex-1 px-3 py-2 border border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
                <button id="upload-url-btn" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Upload</button>
            </div>
            <div class="text-right">
                <button id="close-uploader" class="px-3 py-1 bg-gray-200 rounded-md dark:bg-gray-600 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-500">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/dropzone@6.0.0/dist/dropzone-min.js"></script>
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

    function confirmDelete(id) {
        if (!window.Swal) return;
        Swal.fire({
            title: 'Delete this media?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteMediaConfirmed', { id: id });
            }
        });
    }

    function showDetails(media) {
        if (!window.Swal) return;
        let html = `<p><strong>Name:</strong> ${media.name}</p>` +
            `<p><strong>Mime:</strong> ${media.mime}</p>` +
            `<p><strong>Size:</strong> ${(media.size / 1024).toFixed(2)} KB</p>`;
        if (media.width && media.height) {
            html += `<p><strong>Dimensions:</strong> ${media.width}x${media.height}</p>`;
        }
        html += `<p><a href="${media.url}" target="_blank" class="text-indigo-600">Open file</a></p>`;
        Swal.fire({
            title: 'Media Details',
            html: html,
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('upload-modal');
        document.getElementById('open-uploader').addEventListener('click', () => modal.classList.remove('hidden'));
        document.getElementById('close-uploader').addEventListener('click', () => modal.classList.add('hidden'));

        window.addEventListener('mediaUpdated', e => showToast(e.detail.message));
        window.addEventListener('mediaReplaced', e => showToast(e.detail.message));
        window.addEventListener('mediaDeleted', e => showToast(e.detail.message));

        Dropzone.autoDiscover = false;
        const dz = new Dropzone('#media-dropzone', {
            url: '{{ route('admin.media.upload') }}',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            maxFilesize: 10,
        });

        dz.on('success', (file, response) => {
            showToast(response.message);
            Livewire.dispatch('refreshMedia');
            dz.removeFile(file);
            modal.classList.add('hidden');
        });

        document.getElementById('upload-url-btn').addEventListener('click', (e) => {
            e.preventDefault();
            const url = document.getElementById('media-url').value;
            if (!url) return;
            fetch('{{ route('admin.media.upload') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ url })
            }).then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(res => {
                if (res.ok) {
                    showToast(res.data.message);
                    Livewire.dispatch('refreshMedia');
                    document.getElementById('media-url').value = '';
                    modal.classList.add('hidden');
                } else {
                    alert(res.data.message || 'Upload failed');
                }
            });
        });
    });
</script>
@endpush
