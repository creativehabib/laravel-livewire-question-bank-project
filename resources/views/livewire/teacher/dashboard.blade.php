<div class="space-y-6">
    <h1 class="text-2xl font-bold">Teacher Dashboard</h1>

    @if (session()->has('success'))
        <div class="p-4 rounded-md bg-green-100 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
            <div class="text-sm text-gray-500">মোট প্রশ্ন</div>
            <div class="text-2xl font-semibold">{{ $questionCount }}</div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
            <div class="text-sm text-gray-500">মোট প্রশ্ন সেট</div>
            <div class="text-2xl font-semibold">{{ $questionSetCount }}</div>
        </div>
    </div>

    <div>
        <h2 class="text-xl font-semibold mb-2">বিষয় অনুযায়ী প্রশ্ন</h2>
        <div id="subjectChart" class="h-64"></div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded shadow">
        <div class="flex flex-col gap-4 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold">আপনার প্রশ্ন সেটসমূহ</h2>
                    <p class="text-sm text-gray-500">তৈরীকৃত প্রশ্ন সেটগুলো এখান থেকে ম্যানেজ করুন।</p>
                </div>
                <a
                    wire:navigate
                    href="{{ route('questions.set.create') }}"
                    class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                >
                    নতুন প্রশ্ন সেট
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($questionSets as $questionSet)
                    <div
                        wire:key="question-set-{{ $questionSet->id }}"
                        class="border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between"
                    >
                        <div class="space-y-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $questionSet->name }}</h3>
                            <div class="text-sm text-gray-500 flex flex-wrap gap-x-4 gap-y-1">
                                <span>প্রশ্ন: {{ $questionSet->questions_count }}</span>
                                <span>তৈরীর সময়: {{ $questionSet->created_at?->format('d M, Y h:i A') }}</span>
                                <span>সর্বশেষ আপডেট: {{ $questionSet->updated_at?->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a
                                wire:navigate
                                href="{{ route('questions.view', ['qset' => $questionSet->id]) }}"
                                class="inline-flex items-center justify-center rounded-md border border-indigo-600 px-3 py-1.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50"
                            >
                                এডিট
                            </a>
                            <button
                                wire:click="deleteQuestionSet('{{ $questionSet->id }}')"
                                wire:confirm="আপনি কি নিশ্চিত যে এই প্রশ্ন সেটটি মুছে ফেলতে চান?"
                                class="inline-flex items-center justify-center rounded-md border border-red-600 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50"
                            >
                                ডিলিট
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500 bg-gray-50 dark:bg-gray-700/40 rounded-md p-4">
                        এখনো কোনো প্রশ্ন সেট তৈরি করা হয়নি।
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('livewire:load', () => {
    const data = {
        categories: {!! json_encode($subjects->pluck('name')) !!},
        series: {!! json_encode($subjects->pluck('questions_count')) !!}
    };
    const chart = new ApexCharts(document.querySelector('#subjectChart'), {
        chart: { type: 'bar', height: 250 },
        series: [{ name: 'Questions', data: data.series }],
        xaxis: { categories: data.categories }
    });
    chart.render();
});
</script>
@endpush
