<div x-data="{ showModal: false }"
     @close-modal.window="showModal = false"
     @open-modal.window="showModal = true"
     class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden relative">

    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search topics..."
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 transition-shadow text-sm" />
                </div>

                <select wire:model.live="subjectId"
                        class="px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm font-medium text-gray-600 bg-white">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>

            <button @click="showModal = true; $wire.openModal();"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-lg shadow-sm hover:bg-indigo-700 hover:shadow transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shrink-0">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1.1em" width="1.1em" xmlns="http://www.w3.org/2000/svg"><path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>
                New Topic
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full w-full text-sm align-middle whitespace-nowrap">
            <thead>
            <tr class="bg-gray-50/80 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-300">
                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider text-xs w-24">#ID</th>
                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider text-xs">Topic Name</th>
                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider text-xs">Subject</th>
                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider text-xs">Sub Subject</th>
                <th class="px-6 py-4 text-right font-semibold uppercase tracking-wider text-xs w-32">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
            @forelse($topics as $topic)
                <tr class="hover:bg-indigo-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            #{{ $topic->id }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                        {{ $topic->name }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-md border border-indigo-100 dark:border-indigo-800">
                            {{ $topic->subject->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($topic->subSubject)
                            <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1 rounded-md border border-emerald-100 dark:border-emerald-800">
                                {{ $topic->subSubject->name }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-1">
                        <button type="button" wire:click="edit({{ $topic->id }})"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-md text-indigo-500 hover:text-white hover:bg-indigo-500 transition-colors border border-indigo-100 hover:border-transparent dark:border-gray-600 dark:hover:bg-indigo-600" title="Edit">
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                        <button type="button"
                                @click="
                                    if(typeof Swal !== 'undefined') {
                                        Swal.fire({
                                            title: 'Are you sure?',
                                            text: 'You won\'t be able to revert this!',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            cancelButtonColor: '#6b7280',
                                            confirmButtonText: 'Yes, delete it!'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $wire.delete({{ $topic->id }});
                                            }
                                        });
                                    } else {
                                        if(confirm('Are you sure you want to delete this topic?')) {
                                            $wire.delete({{ $topic->id }});
                                        }
                                    }
                                "
                                class="inline-flex items-center justify-center w-8 h-8 rounded-md text-red-500 hover:text-white hover:bg-red-500 transition-colors border border-red-100 hover:border-transparent dark:border-gray-600 dark:hover:bg-red-600" title="Delete">
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="mb-3 text-gray-300 dark:text-gray-600" height="3em" width="3em" xmlns="http://www.w3.org/2000/svg"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path></svg>
                            <p class="text-lg font-medium">No topics found</p>
                            <p class="text-sm mt-1">Try adjusting your search or filter to find what you're looking for.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($topics->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-800/30">
            {{ $topics->links() }}
        </div>
    @endif

    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal"
                 @click.away="showModal = false"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-90"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-90"
                 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200 dark:border-gray-700">

                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                        {{ $editId ? 'Edit Topic' : 'Create New Topic' }}
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="px-6 py-6 space-y-5">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Subject <span class="text-red-500">*</span></label>
                            <select wire:model.live="modalSubjectId" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                <option value="">-- Choose a Subject --</option>
                                @foreach($subjects as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                            </select>
                            @error('modalSubjectId') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div wire:key="sub-subject-group-{{ $modalSubjectId ?? 'empty' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Sub Subject <span class="text-xs font-normal text-gray-400">(Optional)</span></label>
                            <select wire:model="modalSubSubjectId"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:bg-gray-700 dark:text-white dark:border-gray-600 disabled:opacity-50"
                                    @if(!$modalSubjectId) disabled @endif>
                                <option value="">-- None --</option>
                                @foreach($modalSubSubjects as $ss)
                                    <option value="{{ $ss->id }}">{{ $ss->name }}</option>
                                @endforeach
                            </select>
                            @error('modalSubSubjectId') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Topic Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" placeholder="e.g. Topic 1: Motion" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center gap-2">
                            <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span wire:loading.remove wire:target="save">{{ $editId ? 'Update Topic' : 'Save Topic' }}</span>
                            <span wire:loading wire:target="save">{{ $editId ? 'Updating...' : 'Saving...' }}</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function showToast(message) {
            if (!window.Swal) return;
            Swal.fire({
                toast: true,
                icon: 'success',
                title: message,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        }

        // Create বা Update এর পর টোস্ট দেখানো
        window.addEventListener('topicSaved', e => {
            showToast(e.detail.message);
        });

        // Delete এর পর টোস্ট দেখানো
        window.addEventListener('topicDeleted', e => {
            showToast(e.detail.message || 'Topic has been deleted successfully.');
        });
    </script>
@endpush
