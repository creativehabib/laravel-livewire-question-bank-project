<div class="space-y-6">
    <h1 class="text-2xl font-bold">Student Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
            <div class="text-sm text-gray-500">Last Score</div>
            <div class="text-2xl font-semibold">
                {{ $latest?->score ?? 'N/A' }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h2 class="font-semibold mb-2">Daily Exams</h2>
            <div id="dailyChart" class="h-64"></div>
        </div>
        <div>
            <h2 class="font-semibold mb-2">Weekly Exams</h2>
            <div id="weeklyChart" class="h-64"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:load', () => {
    const dailyData = {
        categories: {!! json_encode($daily->pluck('date')) !!},
        series: {!! json_encode($daily->pluck('total')) !!}
    };
    new ApexCharts(document.querySelector('#dailyChart'), {
        chart: { type: 'bar', height: 250 },
        series: [{ name: 'Exams', data: dailyData.series }],
        xaxis: { categories: dailyData.categories }
    }).render();

    const weeklyData = {
        categories: {!! json_encode($weekly->pluck('week')) !!},
        series: {!! json_encode($weekly->pluck('total')) !!}
    };
    new ApexCharts(document.querySelector('#weeklyChart'), {
        chart: { type: 'line', height: 250 },
        series: [{ name: 'Exams', data: weeklyData.series }],
        xaxis: { categories: weeklyData.categories }
    }).render();
});
</script>
@endpush
