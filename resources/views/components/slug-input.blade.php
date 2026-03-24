@props([
    'table',
    'ignoreId' => null,
    'property' => 'slug'
])

<div x-data="{
    isSlugEditable: false,
    isManual: false,
    slugText: @entangle($property), /* .defer রিমুভ করা হয়েছে */
    checkStatus: '',
    errorMessage: '',
    typingTimer: null,

    checkSlugAvailability() {
        if (!this.slugText || this.slugText.trim() === '') {
            this.checkStatus = '';
            this.errorMessage = '';
            return;
        }

        this.checkStatus = 'checking';

        $wire.validateSlug(this.slugText, '{{ $table }}', '{{ $ignoreId }}').then(result => {
            if (result === true) {
                this.checkStatus = 'available';
                this.errorMessage = '';
            } else if (result) {
                this.checkStatus = 'taken';
                this.errorMessage = result;
            } else {
                this.checkStatus = '';
            }
        });
    }
}">
    <label class="block text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 border-b pb-1">Question Slug (URL)</label>
    <div class="flex items-center gap-2">
        <div class="relative flex-1">
            <input type="text"
                   id="slug_input"
                   x-model="slugText"
                   x-bind:tabindex="isSlugEditable ? '0' : '-1'"
                   @input="
                       isManual = true;
                       $el.setAttribute('data-manual', 'true');
                       clearTimeout(typingTimer);
                       typingTimer = setTimeout(() => checkSlugAvailability(), 800);
                   "
                   @blur="
                       if(isManual) {
                           slugText = generateSlug(slugText);
                           checkSlugAvailability();
                       }
                   "
                   @paste="
                       setTimeout(() => {
                           slugText = generateSlug(slugText);
                           checkSlugAvailability();
                       }, 50);
                   "
                   @slug-auto-updated.window="
                       if (!isManual) {
                           slugText = $event.detail;
                           clearTimeout(typingTimer);
                           typingTimer = setTimeout(() => checkSlugAvailability(), 1000);
                       }
                   "
                   :class="isSlugEditable ? 'bg-white focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 text-gray-900' : 'bg-gray-100 text-gray-500 pointer-events-none select-none dark:bg-gray-600'"
                   class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm transition-colors dark:border-gray-600 dark:text-white"
                   placeholder="auto-generated-slug-will-appear-here" />
        </div>

        <button type="button"
                @click="
                    isSlugEditable = !isSlugEditable;
                    if(isSlugEditable) {
                        setTimeout(() => document.getElementById('slug_input').focus(), 50);
                    } else {
                        slugText = generateSlug(slugText);
                        checkSlugAvailability();
                    }
                "
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors shrink-0">

            <svg x-show="!isSlugEditable" class="h-4 w-4 mr-1 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
            <span x-show="!isSlugEditable">Edit</span>

            <svg x-show="isSlugEditable" style="display: none;" class="h-4 w-4 mr-1 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            <span x-show="isSlugEditable" style="display: none;">Done</span>
        </button>
    </div>

    <div class="mt-1.5 flex items-center min-h-[20px] transition-all">
        <div x-show="checkStatus === 'checking'" style="display: none;" class="text-xs text-blue-600 dark:text-blue-400 font-medium flex items-center gap-1">
            <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Checking availability...
        </div>

        <div x-show="checkStatus === 'available'" style="display: none;" class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            Slug is available!
        </div>

        <div x-show="checkStatus === 'taken'" style="display: none;" class="text-xs text-red-500 font-semibold flex items-center gap-1">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span x-text="errorMessage"></span>
        </div>

        @error($property)
        <div x-show="checkStatus === '' || checkStatus === 'available'" class="text-xs text-red-500 font-semibold flex items-center gap-1">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
