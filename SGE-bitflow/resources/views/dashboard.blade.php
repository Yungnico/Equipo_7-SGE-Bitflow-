@extends('adminlte::page')
@section('plugins.ChartJS', true)

{{-- Set the page title --}}
@section('title', 'Dashboard')

@section('content_header')
    @can('dashboard')
        <h1>Futuras Métricas</h1>
    @endcan
@stop

@section('content')
<div class="card">
    <div class="card-body">

        <canvas id="myChart"></canvas>
    </div>
</div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
            labels: ['1', '2', '3', '4', '5', '6'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
            },
            {
                label: 'Another Dataset',
                data: [5, 15, 10, 8, 12, 7],
                borderWidth: 1
            },
            {
                label: 'Third Dataset',
                data: [2, 3, 20, 5, 1, 4],
                borderWidth: 1
            },
            {
                label: 'Fourth Dataset',
                data: [3, 10, 13, 15, 7, 8],
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
  });
        console.log(Chart.version)
    });
</script>
@stop