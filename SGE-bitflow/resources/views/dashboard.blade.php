@extends('adminlte::page')
@section('plugins.ChartJS', true)

{{-- Set the page title --}}
@section('title', 'Dashboard')

@section('content_header')
    
@stop

@section('content')
<div class="row py-5">
    <!-- Area Chart -->
    <div class="col-md-6">
        <x-adminlte-card title="Area Chart" theme="primary" icon="fas fa-chart-area" >
            <canvas id="areaChart"></canvas>
        </x-adminlte-card>
    </div>

    <!-- Line Chart -->
    <div class="col-md-6">
        <x-adminlte-card title="Line Chart" theme="primary" icon="fas fa-chart-area" >
            <canvas id="lineChart"></canvas>
        </x-adminlte-card>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];

    const dataCommon = {
        labels: labels,
        datasets: [
            {
                label: 'Blue Data',
                data: [30, 48, 40, 19, 86, 27, 90],
                fill: true,
                borderColor: 'rgba(60,141,188,1)',
                backgroundColor: 'rgba(60,141,188,0.4)',
                tension: 0.4
            },
            {
                label: 'Gray Data',
                data: [65, 59, 80, 81, 56, 55, 40],
                fill: true,
                borderColor: 'rgba(210, 214, 222, 1)',
                backgroundColor: 'rgba(210, 214, 222, 0.5)',
                tension: 0.4
            },
            {
                label: 'Green Data',
                data: [28, 48, 40, 19, 86, 27, 90],
                fill: true,
                borderColor: 'rgba(0,128,0,1)',
                backgroundColor: 'rgba(0,128,0,0.4)',
                tension: 0.4
            },
            {
                label: 'Red Data',
                data: [18, 48, 77, 9, 100, 27, 40],
                fill: true,
                borderColor: 'rgba(255,0,0,1)',
                backgroundColor: 'rgba(255,0,0,0.4)',
                tension: 0.4
            }
        ]
    };

    new Chart(document.getElementById('areaChart'), {
        type: 'line',
        data: JSON.parse(JSON.stringify(dataCommon)), // Clona para modificar
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('lineChart'), {
        type: 'bar',
        data: JSON.parse(JSON.stringify(dataCommon)),
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection