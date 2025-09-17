<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Email</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Role</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Status</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->id }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            <select class="px-2 py-1 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" wire:change="changeRole({{ $user->id }}, $event.target.value)">
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption->value }}" @selected($user->role === $roleOption)>{{ ucfirst($roleOption->value) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-2">
                            @if($user->isSuspended())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200">
                                    Suspended
                                </span>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Until {{ $user->suspended_until?->timezone(config('app.timezone'))->format('M d, Y h:i A') }}
                                </div>
                            @elseif($user->suspended_until)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-200">
                                    Suspension expired
                                </span>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Ended {{ $user->suspended_until?->timezone(config('app.timezone'))->format('M d, Y h:i A') }}
                                </div>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-200">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2 space-y-2 sm:space-y-0 sm:space-x-2 sm:flex sm:items-center">
                            <button type="button"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    wire:click="editUser({{ $user->id }})">
                                Edit
                            </button>

                            <button type="button"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-amber-500 text-white text-xs font-medium hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                                    wire:click="openSuspendModal({{ $user->id }})">
                                {{ $user->isSuspended() || $user->suspended_until ? 'Update Suspension' : 'Suspend' }}
                            </button>

                            @if($user->suspended_until)
                                <button type="button"
                                        class="inline-flex items-center px-3 py-1.5 rounded-md bg-green-600 text-white text-xs font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        wire:click="clearSuspension({{ $user->id }})">
                                    Lift Suspension
                                </button>
                            @endif

                            <button type="button"
                                    onclick="confirmDelete({{ $user->id }})"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-600 text-white text-xs font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>

    <x-modal name="edit-user" :show="$showEditModal" max-width="3xl" focusable>
    <form wire:submit.prevent="updateUser" class="p-6 space-y-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit user information</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update the selected user's profile information, role, and contact details.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="edit-name" value="Name" />
                <x-text-input id="edit-name" type="text" class="mt-1 block w-full" wire:model.defer="editForm.name" />
                <x-input-error :messages="$errors->get('editForm.name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="edit-email" value="Email" />
                <x-text-input id="edit-email" type="email" class="mt-1 block w-full" wire:model.defer="editForm.email" />
                <x-input-error :messages="$errors->get('editForm.email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="edit-role" value="Role" />
                <select id="edit-role" wire:model.defer="editForm.role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                    <option value="">Select role</option>
                    @foreach($roles as $roleOption)
                        <option value="{{ $roleOption->value }}">{{ ucfirst($roleOption->value) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('editForm.role')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="edit-phone" value="Phone" />
                <x-text-input id="edit-phone" type="text" class="mt-1 block w-full" wire:model.defer="editForm.phone" />
                <x-input-error :messages="$errors->get('editForm.phone')" class="mt-2" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="edit-address" value="Address" />
                <x-text-input id="edit-address" type="text" class="mt-1 block w-full" wire:model.defer="editForm.address" />
                <x-input-error :messages="$errors->get('editForm.address')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="edit-institution" value="Institution" />
                <x-text-input id="edit-institution" type="text" class="mt-1 block w-full" wire:model.defer="editForm.institution_name" />
                <x-input-error :messages="$errors->get('editForm.institution_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="edit-division" value="Division" />
                <x-text-input id="edit-division" type="text" class="mt-1 block w-full" wire:model.defer="editForm.division" />
                <x-input-error :messages="$errors->get('editForm.division')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="edit-district" value="District" />
                <x-text-input id="edit-district" type="text" class="mt-1 block w-full" wire:model.defer="editForm.district" />
                <x-input-error :messages="$errors->get('editForm.district')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="edit-thana" value="Thana" />
                <x-text-input id="edit-thana" type="text" class="mt-1 block w-full" wire:model.defer="editForm.thana" />
                <x-input-error :messages="$errors->get('editForm.thana')" class="mt-2" />
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <x-secondary-button type="button" wire:click="closeEditModal">Cancel</x-secondary-button>
            <x-primary-button>Save changes</x-primary-button>
        </div>
    </form>
</x-modal>

    <x-modal name="suspend-user" :show="$showSuspendModal" focusable>
    <form wire:submit.prevent="saveSuspension" class="p-6 space-y-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Schedule a suspension</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Choose when the user's account should remain suspended. The user will regain access automatically after the selected time.</p>
        </div>

        <div>
            <x-input-label for="suspend-until" value="Suspend until" />
            <x-text-input id="suspend-until" type="datetime-local" class="mt-1 block w-full" wire:model.defer="suspendForm.until" />
            <x-input-error :messages="$errors->get('suspendForm.until')" class="mt-2" />
        </div>

        <div class="flex justify-end space-x-3">
            <x-secondary-button type="button" wire:click="closeSuspendModal">Cancel</x-secondary-button>
            <x-primary-button>Save suspension</x-primary-button>
        </div>
    </form>
</x-modal>
</div>

@push('scripts')
<script>
    function showToast(message, type = 'success') {
        if (!window.Swal || !message) return;
        Swal.fire({
            toast: true,
            icon: type,
            title: message,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
    }

    function confirmDelete(id) {
        if (!window.Swal) return;
        Swal.fire({
            title: 'Delete this user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteUserConfirmed', { userId: id });
            }
        });
    }

    window.addEventListener('roleUpdated', e => {
        showToast(e.detail.message || 'Role updated successfully.', e.detail.type || 'success');
    });

    window.addEventListener('userUpdated', e => {
        showToast(e.detail.message || 'User updated successfully.', e.detail.type || 'success');
    });

    window.addEventListener('userSuspensionUpdated', e => {
        showToast(e.detail.message || 'User suspension updated.', e.detail.type || 'success');
    });

    window.addEventListener('userDeleted', e => {
        showToast(e.detail.message || 'User deleted successfully.', e.detail.type || 'success');
    });
</script>
@endpush
