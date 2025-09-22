<div class="max-w-4xl mx-auto my-8 p-10 bg-white font-bangla">

    <div class="text-center mb-6 pb-4 border-b">
        <h1 class="text-3xl font-bold text-gray-800">{{ $instituteName }}</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mt-2">{{ $questionSet->name }}</h2>

        <div class="text-lg text-gray-600 mt-2">
            <p>বিষয়: {{ $subSubject->name ?? 'N/A' }} {{ $subSubject->name ? '(' . $subject->name . ')' : '' }}</p>
            <p>অধ্যায়: {{ $chapter->name }}</p>
        </div>
    </div>

    <div class="flex justify-between items-center text-md text-gray-700 font-medium mb-8">
        <span>সময়: ২ ঘন্টা ৩০ মিনিট</span>
        <span>পূর্ণমান: {{ $questionSet->questions->count('marks') }}</span>
    </div>

    <div class="space-y-8">
        @forelse ($questionSet->questions as $question)
            <div class="question-item">
                <div class="flex items-start text-lg">
                    <span class="font-bold mr-2">{{ $loop->iteration }}.</span>
                    <div class="flex-1">{!! $question->title !!}</div>
                </div>
                @if($question->options->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mt-4 pl-8">
                        @foreach ($question->options as $option)
                            <div class="p-2 flex items-center gap-1 rounded-lg">
                                <div class="flex items-center justify-center h-5 w-5 border rounded-full p-0.5 {{ $option->is_correct ? 'bg-gray-700 text-white border-gray-700' : 'border-gray-600' }}">{{ mb_chr(2453 + $loop->index) }}</div>
                                <div>{!! $option->option_text !!}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">
                এই প্রশ্নপত্রে এখনো কোনো প্রশ্ন যুক্ত করা হয়নি।
            </div>
        @endforelse
    </div>
</div>
