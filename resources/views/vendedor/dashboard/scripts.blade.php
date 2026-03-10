@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('ventasHoraChart'), {
    type: 'bar',
            data: {
            labels: {!! json_encode($labelsHoras) !!},
                    datasets: [{
                    label: 'Ventas ($)',
                            data: {!! json_encode($dataHoras) !!},
                            borderWidth: 1
                    }]
            },
            options: {
            responsive: true,
                    plugins: {
                    legend: {
                    display: true
                    }
                    }
            }
    });
</script>
@endpush
