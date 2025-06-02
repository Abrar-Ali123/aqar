<div class="card shadow-sm mb-4">
    <div class="card-header bg-light fw-bold">@lang('عدد زيارات صفحات المنشأة خلال 7 أيام')</div>
    <div class="card-body">
        <canvas id="facilityVisitsChart" height="80"></canvas>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('facilityVisitsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: '@lang('عدد الزيارات')',
                    data: @json($data),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.08)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: { beginAtZero: true, precision: 0 }
                }
            }
        });
    });
</script>
@endpush
