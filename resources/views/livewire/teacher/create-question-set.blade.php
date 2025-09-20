<div class="w-full max-w-4xl mx-auto my-12 bg-white rounded-xl shadow-2xl overflow-hidden">
    <div class="bg-gray-200 px-4 py-3 flex items-center">
        <div class="flex space-x-2">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
            <div class="w-3 h-3 rounded-full bg-green-500"></div>
        </div>
        <div class="flex-grow text-right text-xs text-gray-500">v.0.8.৮</div>
    </div>

    <div class="w-full">
        <div class="bg-slate-800 text-white text-center py-8 px-6">
            <h1 class="text-4xl font-bold mb-2">১ ক্লিকে প্রশ্ন তৈরীর সফটওয়্যার !</h1>
            <p class="text-lg text-slate-300 mb-6">জ্ঞান লাভের পথ আরও সোজা হোক! 📚</p>
            <button class="bg-white text-slate-800 font-semibold px-6 py-2 rounded-md hover:bg-gray-200 transition-colors duration-300">
                Subscribe Now!
            </button>
        </div>

        <div class="p-6 relative">
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm p-2 rounded-md flex items-center justify-center mb-8">
                <span>নিচের ইনপুট ফিল্ড গুলো সিলেক্ট করে সাবমিট করুন</span>
                <span class="flex items-center font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        সর্বশেষ প্রশ্ন যুক্ত হয়েছে ✅ 5 minutes ago
                    </span>
            </div>
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="saveQuestionSet" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">প্রোগ্রাম/পরীক্ষার নাম লিখুন *</label>
                    <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700">শ্রেণি</label>
                        <select wire:model="selectedClass" id="class" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- শ্রেণি নির্বাচন করুন --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">বিষয়</label>
                        <select wire:model="selectedSubject" id="subject" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- বিষয় নির্বাচন করুন --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="chapter" class="block text-sm font-medium text-gray-700">অধ্যায়</label>
                        <select wire:model.defer="selectedChapter" id="chapter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- অধ্যায় নির্বাচন করুন --</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="w-1/3">
                        <label for="type" class="block text-sm font-medium text-gray-700">টাইপ</label>
                        <select wire:model.defer="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="mcq">MCQ</option>
                            <option value="cq">Creative</option>
                            <option value="combine">Combine</option>
                        </select>
                    </div>
                    <div class="w-2/3">
                        <label for="quantity" class="invisible block text-sm font-medium text-gray-700">পরিমাণ</label>
                        <input type="number" wire:model.defer="quantity" id="quantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full mt-4 bg-emerald-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-emerald-700">
                        প্রশ্ন তৈরী করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
