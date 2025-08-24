<div class="space-y-6">
    <h1 class="text-2xl font-bold">Teacher Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
            <div class="text-sm text-gray-500">Total Questions</div>
            <div class="text-2xl font-semibold">{{ $questionCount }}</div>
        </div>
    </div>

    <div>
        <h2 class="text-xl font-semibold mb-2">Questions by Subject</h2>
        <div id="subjectChart" class="h-64"></div>
    </div>

    <livewire:teacher.chat />
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
