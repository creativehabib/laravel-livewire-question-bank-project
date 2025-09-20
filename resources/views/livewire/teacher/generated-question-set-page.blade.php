<div class="max-w-4xl mx-auto my-12 p-10 bg-white rounded-xl shadow-lg border">

    <div class="text-center mb-4">
        <h1 class="text-3xl font-bold text-gray-800">{{ $questionSet->user->institution_name ?? 'N/A'}}</h1>
        <p class="text-lg text-gray-600 mt-1">{{ $subject->name ?? 'N/A'}}</p>
        <p class="text-lg text-gray-600">{{ $subSubject->name ?? 'N/A'}}</p>
        <p class="text-lg text-gray-600">{{ $chapter->name ?? 'N/A'}}</p>
    </div>

    <div class="flex justify-between items-center text-md text-gray-700 font-medium mb-4 pb-4 border-b">
        <span>সময়: ১ ঘন্টা ৪০ মিনিট</span>
        <span>পূর্ণমান: ১০০</span>
    </div>

    <div class="text-center text-gray-500 mb-10">
        <p>প্রশ্নপত্রে কোনো প্রকার দাগ/চিহ্ন দেয়া যাবেনা !</p>
    </div>

    <div class="text-center">
        <div class="flex justify-center items-center text-green-600 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <h2 class="text-2xl font-semibold">প্রশ্নসেট তৈরী হয়েছে!</h2>
        </div>

        <p class="text-gray-600 mb-6">নিচের বাটনে ক্লিক করে ডেটাবেজ থেকে প্রশ্ন যুক্ত করুন</p>

        <a href="{{ route('questions.view', $questionSet->id) }}" class="inline-block bg-emerald-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-emerald-700 transition-colors duration-300 text-lg shadow-md">
            প্রশ্ন যুক্ত করুন
        </a>
    </div>
</div>
