<div>
    {{-- ✅ Stats Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm flex items-center justify-between hover:shadow-lg hover:-translate-y-1 transition-all">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Questions</h3>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($questionsCount) }}</p>
            </div>
            <div class="p-3 bg-indigo-100 dark:bg-indigo-500/20 rounded-full">
                <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm flex items-center justify-between hover:shadow-lg hover:-translate-y-1 transition-all">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Subjects</h3>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($subjectsCount) }}</p>
            </div>
            <div class="p-3 bg-emerald-100 dark:bg-emerald-500/20 rounded-full">
                <svg class="w-6 h-6 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                </svg>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm flex items-center justify-between hover:shadow-lg hover:-translate-y-1 transition-all">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Students</h3>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($usersCount) }}</p>
            </div>
            <div class="p-3 bg-sky-100 dark:bg-sky-500/20 rounded-full">
                <svg class="w-6 h-6 text-sky-500 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm flex items-center justify-between hover:shadow-lg hover:-translate-y-1 transition-all">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">New Today</h3>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">+{{ number_format($newQuestionsToday) }}</p>
            </div>
            <div class="p-3 bg-orange-100 dark:bg-orange-500/20 rounded-full">
                <svg class="w-6 h-6 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- ✅ Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Usage Trend</h3>
            <div id="usageChart" class="h-80"></div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Questions by Subject</h3>
            <div id="subjectChart" class="h-80"></div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Top Question Views</h3>
            <div id="viewChart" class="h-80"></div>
        </div>
    </div>

    {{-- ✅ Chat --}}
    <livewire:admin.chat />

    {{-- ✅ Recent Questions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-x-auto mt-6">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Questions</h3>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="p-4 font-semibold">Question</th>
                <th class="p-4 font-semibold">Subject</th>
                <th class="p-4 font-semibold">Created</th>
                <th class="p-4 font-semibold">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($recentQuestions as $question)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="p-4">{!! $question->title !!}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            {{ $question->subject->name ?? '—' }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-500 dark:text-gray-400">{{ $question->created_at->diffForHumans() }}</td>
                    <td class="p-4 space-x-2">
                        <a wire:navigate href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-500 hover:text-indigo-700 font-medium">Edit</a>
                        <button class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500 dark:text-gray-400">No recent questions found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        function renderAdminCharts() {
            if (!document.querySelector('#usageChart')) return;

            const messageCounts = @json($messageCounts);

            const usageOptions = {
                chart: { type: 'line', height: 320 },
                series: [{ name: 'Messages', data: Object.values(messageCounts) }],
                xaxis: { categories: Object.keys(messageCounts) }
            };
            new ApexCharts(document.querySelector('#usageChart'), usageOptions).render();

            const subjectOptions = {
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Questions', data: @json($subjectChartData->pluck('questions_count')) }],
                xaxis: { categories: @json($subjectChartData->pluck('name')) }
            };
            new ApexCharts(document.querySelector('#subjectChart'), subjectOptions).render();

            const viewOptions = {
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Views', data: @json($viewChartData->pluck('views')) }],
                xaxis: { categories: @json($viewChartData->pluck('title')) }
            };
            new ApexCharts(document.querySelector('#viewChart'), viewOptions).render();
        }

        document.addEventListener('livewire:navigated', renderAdminCharts);

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', renderAdminCharts);
        } else {
            renderAdminCharts();
        }
    </script>
@endpush
