@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
<div class="row pt-5">
    <!-- Area Chart con botón toggle -->
    <div class="col" id="areaChartContainer">
        <x-adminlte-card title="KPIs Cotizaciones" theme="info" icon="fas fa-chart-bar" collapsible>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <select id="quickRange" class="form-control w-25 mr-2">
                    <option value="custom">Seleccionar rango personalizado</option>
                    <option value="last7">Últimos 7 días</option>
                    <option value="last30">Últimos 30 días</option>
                    <option value="last90">Últimos 90 días</option>
                    <option value="thisYear">Año actual</option>
                </select>

                <input type="text" id="dateRange" class="form-control w-50" placeholder="Seleccionar rango de fechas" disabled>
                <button class="btn btn-sm btn-outline-info ml-2" id="cambiar_vista" onclick="toggleKpiView()">Ver Gráfico</button>
            </div>

            <div class="position-relative" id="kpiChartContainer" style=" display:none;  min-height: 300px;">
                <canvas id="kpiChart" style="height: 100% !important;"></canvas>
            </div>

            <div id="kpiTotalContainer" style="font-size: 18px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(54, 162, 235, 0.2);">
                            <div class="inner">
                                <h3 id="Generadas">150</h3>
                                <p>Cantidad Cotizaciones Generadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(255, 205, 86, 0.2);">
                            <div class="inner">
                                <h3 id="Pendiente">44</h3>
                                <p>Cantidad Cotizaciones Pend Pago</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-comment-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(75, 192, 192, 0.2);">
                            <div class="inner">
                                <h3 id="Pagadas">150</h3>
                                <p>Cantidad Cotizaciones Pagadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(255, 99, 132, 0.2);">
                            <div class="inner">
                                <h3 id="Rechazadas">44</h3>
                                <p>Cantidad Cotizaciones Rechazadas</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-window-close"></i>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </x-adminlte-card>
    </div>
    <div class="col-md-6" style="display: none;" id="hola">
        <x-adminlte-card title="Kpi Cotizacciones" theme="primary" icon="fas fa-chart-bar" collapsible>
            <div id="kpiTotalContainer" class="mt-5" style="font-size: 18px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(54, 162, 235, 0.2);">
                            <div class="inner">
                                <h3 id="Generadas1">150</h3>
                                <p>Cantidad Cotizaciones Generadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(255, 205, 86, 0.2);">
                            <div class="inner">
                                <h3 id="Pendiente1">44</h3>
                                <p>Cantidad Cotizaciones Pend Pago</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-comment-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(75, 192, 192, 0.2);">
                            <div class="inner">
                                <h3 id="Pagadas1">150</h3>
                                <p>Cantidad Cotizaciones Pagadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(255, 99, 132, 0.2);">
                            <div class="inner">
                                <h3 id="Rechazadas1">44</h3>
                                <p>Cantidad Cotizaciones Rechazadas</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-window-close"></i>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </x-adminlte-card>
    </div>
</div>
<div class="row">
    <div class="col" id="facturaChartContainer">
        <x-adminlte-card title="KPIs Facturas" theme="primary" icon="fas fa-file-invoice-dollar" collapsible>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <select id="facturaQuickRange" class="form-control w-25 mr-2">
                    <option value="custom">Rango personalizado</option>
                    <option value="last7">Últimos 7 días</option>
                    <option value="last30">Últimos 30 días</option>
                    <option value="last90">Últimos 90 días</option>
                    <option value="thisYear">Año actual</option>
                </select>
                <input type="text" id="facturaDateRange" class="form-control w-50" placeholder="Seleccionar rango de fechas" disabled>
                <button class="btn btn-sm btn-outline-primary ml-2" id="facturaToggle" onclick="toggleFacturaView()">Ver Gráfico</button>
            </div>
    
            <div id="facturaChartCanvasContainer" class="position-relative" style="display:none; min-height: 300px;">
                <canvas id="facturaChart" style="height: 100% !important;"></canvas>
            </div>
    
            <div id="facturaTotalsContainer" style="font-size: 18px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(54, 162, 235, 0.2);">
                            <div class="inner">
                                <h3 id="fact_emitidas">0</h3>
                                <p>Cant. Facturas Emitidas</p>
                            </div>
                            <div class="icon"><i class="fas fa-file-invoice"></i></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(255, 205, 86, 0.2);">
                            <div class="inner">
                                <h3 id="fact_pendientes">0</h3>
                                <p>Cant. Facturas Pend. Pago</p>
                            </div>
                            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(75, 192, 192, 0.2);">
                            <div class="inner">
                                <h3 id="fact_pagadas">0</h3>
                                <p>Cant. Facturas Pagadas</p>
                            </div>
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(255, 99, 132, 0.2);">
                            <div class="inner">
                                <h3 id="fact_anuladas">0</h3>
                                <p>Cant. Facturas Anuladas</p>
                            </div>
                            <div class="icon"><i class="fas fa-ban"></i></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 text-center">
                        <div class="small-box" style="background-color:	rgba(0, 123, 255, 0.2);">
                            <div class="inner">
                                <strong>Total Neto:</strong>
                                <h3 id="fact_neto">0</h3>
                            </div>
                            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="small-box" style="background-color:	rgba(255, 159, 64, 0.2);">
                            <div class="inner">
                                <strong>Total IVA:</strong>
                                <h3 id="fact_iva">0</h3>
                            </div>
                            <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="small-box" style="background-color:	rgba(40, 167, 69, 0.2);">
                            <div class="inner">
                                <h3 id="fact_total">0</h3>
                                <strong>Total Facturado:</strong>
                            </div>
                            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </x-adminlte-card>
    </div>
    <div class="col-md-6" id="facturakpis" style="display: none;">
        <x-adminlte-card title="KPIs Facturas" theme="primary" icon="fas fa-file-invoice-dollar" collapsible>
            <div id="facturaTotalsContainer" style="font-size: 18px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(54, 162, 235, 0.2);">
                            <div class="inner">
                                <h3 id="fact_emitidas1">0</h3>
                                <p>Cant. Facturas Emitidas</p>
                            </div>
                            <div class="icon"><i class="fas fa-file-invoice"></i></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(255, 205, 86, 0.2);">
                            <div class="inner">
                                <h3 id="fact_pendientes1">0</h3>
                                <p>Cant. Facturas Pend. Pago</p>
                            </div>
                            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(75, 192, 192, 0.2);">
                            <div class="inner">
                                <h3 id="fact_pagadas1">0</h3>
                                <p>Cant. Facturas Pagadas</p>
                            </div>
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box" style="background-color:rgba(255, 99, 132, 0.2);">
                            <div class="inner">
                                <h3 id="fact_anuladas1">0</h3>
                                <p>Cant. Facturas Anuladas</p>
                            </div>
                            <div class="icon"><i class="fas fa-ban"></i></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 text-center">
                        <div class="small-box" style="background-color:	rgba(0, 123, 255, 0.2);">
                            <div class="inner">
                                <strong>Total Neto:</strong>
                                <h3 id="fact_neto1">0</h3>
                            </div>
                            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="small-box" style="background-color:	rgba(255, 159, 64, 0.2);">
                            <div class="inner">
                                <strong>Total IVA:</strong>
                                <h3 id="fact_iva1">0</h3>
                            </div>
                            <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="small-box" style="background-color:	rgba(40, 167, 69, 0.2);">
                            <div class="inner">
                                <h3 id="fact_total1">0</h3>
                                <strong>Total Facturado:</strong>
                            </div>
                            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </x-adminlte-card>
    </div>
</div>
<div class="row">
        <!-- Gráfico de torta por cliente -->
        <div class="col-sm-4">
            <x-adminlte-card title="Montos facturados por cliente" theme="teal" icon="fas fa-chart-pie" collapsible>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <select id="rango_clientes" class="form-control ">
                        <option value="custom">Seleccionar rango personalizado</option>
                        <option value="last7">Últimos 7 días</option>
                        <option value="last30">Últimos 30 días</option>
                        <option value="last90">Últimos 90 días</option>
                        <option value="thisYear">Año actual</option>
                    </select>

                    <input type="text" id="rango_clientes_fp" class="form-control w-50" placeholder="Seleccionar rango de fechas" disabled>
                </div>
                <canvas id="graficoClientes" style="min-height: 250px; max-height: 250px;"></canvas>
            </x-adminlte-card>
        </div>

        <!-- Gráfico de serie comparativo año actual vs anterior -->
        <div class="col-sm-4">
            <x-adminlte-card title="Comparativo Año Actual vs Anterior" theme="info" icon="fas fa-chart-line" collapsible>
                <canvas id="graficoComparativo" style="min-height: 300px;"></canvas>
            </x-adminlte-card>
        </div>

        <!-- Gráfico de barras: Facturado vs Ingresos -->
        <div class="col-sm-4">
            <x-adminlte-card title="Total Facturado vs Ingresos" theme="warning" icon="fas fa-chart-bar" collapsible>
                <canvas id="graficoFacturadoIngresos" style="min-height: 300px;"></canvas>
            </x-adminlte-card>
        </div>
</div>

@endsection

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <!-- Estilos -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Variables globales para el gráfico de KPIs

        let showingChart = true;
        let chartInstance = null;
        let startDate = null;
        let endDate = null;

        function toggleKpiView() {
            showingChart = !showingChart;
            document.getElementById('kpiTotalContainer').style.display = showingChart ? 'block' : 'none';
            document.getElementById('kpiChartContainer').style.display = showingChart ? 'none' : 'block';
            document.getElementById('hola').style.display = showingChart ? 'none' : 'block';
            document.getElementById('areaChartContainer').classList.toggle('col-md-6', !showingChart);
            document.getElementById('cambiar_vista').textContent = showingChart ? 'Ver Gráfico' : 'Ver Totales';
        }

        function setRangeByQuickOption(value) {
            const today = new Date();
            let start = new Date();
            let end = new Date();

            switch (value) {
                case 'last7':
                    start.setDate(today.getDate() - 6);
                    break;
                case 'last30':
                    start.setDate(today.getDate() - 29);
                    break;
                case 'last90':
                    start.setDate(today.getDate() - 89);
                    break;
                case 'thisYear':
                    start = new Date(today.getFullYear(), 0, 1);
                    break;
                case 'custom':
                    document.getElementById("dateRange").disabled = false;
                    return;
            }
            end.setDate(end.getDate() + 1); 
            startDate = start.toISOString().split('T')[0];
            endDate = end.toISOString().split('T')[0];
            document.getElementById("dateRange").value = `${startDate} to ${endDate}`;
            document.getElementById("dateRange").disabled = true;
            loadKpiChart();
        }

        function loadKpiChart() {
            const params = new URLSearchParams();
            if (startDate && endDate) {
                params.append('inicio', startDate);
                params.append('fin', endDate);
            }

            fetch(`/cotizaciones/kpi?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    const ctx = document.getElementById('kpiChart').getContext('2d');
                    if (chartInstance) chartInstance.destroy();

                    chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: res.labels,
                            datasets: [{
                                label: 'Cantidad',
                                data: res.data,
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 205, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(255, 99, 132, 0.2)'
                                ],
                                borderColor: [
                                    'rgb(54, 162, 235)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(255, 99, 132)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: (() => {
                                        const max = Math.max(...res.data);
                                        return max < 3 ? 3 : max + 1;
                                    })(),
                                    ticks: {
                                        stepSize: 1,
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });

                    const Generadas = document.getElementById('Generadas');
                    const Pendiente = document.getElementById('Pendiente');
                    const Pagadas = document.getElementById('Pagadas');
                    const Rechazadas = document.getElementById('Rechazadas');
                    const Generadas1 = document.getElementById('Generadas1');
                    const Pendiente1 = document.getElementById('Pendiente1');
                    const Pagadas1 = document.getElementById('Pagadas1');
                    const Rechazadas1 = document.getElementById('Rechazadas1');

                    res.labels.forEach((label, index) => {
                        const value = res.data[index] ?? 0;
                        if (label === 'Generadas') {
                            Generadas.textContent = value;
                            Generadas1.textContent = value;
                        } else if (label === 'Pend. Pago') {
                            Pendiente.textContent = value;
                            Pendiente1.textContent = value;
                        } else if (label === 'Pagadas') {
                            Pagadas.textContent = value;
                            Pagadas1.textContent = value;
                        } else if (label === 'Rechazadas') {
                            Rechazadas.textContent = value;
                            Rechazadas1.textContent = value;
                        }
                    });
                });
        }

        document.addEventListener("DOMContentLoaded", function () {
            flatpickr("#dateRange", {
                mode: "range",
                dateFormat: "Y-m-d",
                onChange: function (selectedDates) {
                    if (selectedDates.length === 2) {
                        startDate = selectedDates[0].toISOString().split('T')[0];
                        endDate = selectedDates[1].toISOString().split('T')[0];
                        loadKpiChart();
                    }
                }
            });
            document.getElementById("quickRange").addEventListener("change", function () {
                setRangeByQuickOption(this.value);
            });

            // Cargar por defecto últimos 30 días
            document.getElementById("quickRange").value = "last30";
            setRangeByQuickOption("last30");
        });



        // Variables globales para el gráfico de Facturas
        // y los totales de facturas
        // Inicializar variables para el gráfico de facturas

        let facturaChartInstance = null;
        let facturaStart = null;
        let facturaEnd = null;
        let showingFacturaChart = true;

        function toggleFacturaView() {
            showingFacturaChart = !showingFacturaChart;
            document.getElementById('facturaChartCanvasContainer').style.display = showingFacturaChart ? 'none' : 'block';
            document.getElementById('facturaTotalsContainer').style.display = showingFacturaChart ? 'block' : 'none';
            document.getElementById('facturaToggle').textContent = showingFacturaChart ? 'Ver Gráfico' : 'Ver Totales';
            document.getElementById('facturakpis').style.display = showingFacturaChart ? 'none' : 'block';
            document.getElementById('facturaChartContainer').classList.toggle('col-md-6', !showingFacturaChart);
            
        }

        function setFacturaRange(option) {
            const today = new Date();
            let start = new Date();
            let end = new Date();

            switch (option) {
                case 'last7': start.setDate(today.getDate() - 6); break;
                case 'last30': start.setDate(today.getDate() - 29); break;
                case 'last90': start.setDate(today.getDate() - 89); break;
                case 'thisYear': start = new Date(today.getFullYear(), 0, 1); break;
                case 'custom':
                    document.getElementById('facturaDateRange').disabled = false;
                    return;
            }

            // Forzar inclusión del día actual
            end.setDate(end.getDate() + 1);

            facturaStart = start.toISOString().split('T')[0];
            facturaEnd = end.toISOString().split('T')[0];
            document.getElementById('facturaDateRange').value = `${facturaStart} to ${facturaEnd}`;
            document.getElementById('facturaDateRange').disabled = true;

            loadFacturaKpis();
        }

        function loadFacturaKpis() {
            const params = new URLSearchParams();
            if (facturaStart && facturaEnd) {
                params.append('inicio', facturaStart);
                params.append('fin', facturaEnd);
            }

            fetch(`/facturas/kpi?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    const [
                        emitidas, pendientes, pagadas, anuladas,
                        totalNeto, totalIVA, totalFacturado
                    ] = res.data;

                    // Totales
                    document.getElementById('fact_emitidas').textContent = emitidas;
                    document.getElementById('fact_pendientes').textContent = pendientes;
                    document.getElementById('fact_pagadas').textContent = pagadas;
                    document.getElementById('fact_anuladas').textContent = anuladas;
                    document.getElementById('fact_neto').textContent = totalNeto.toLocaleString();
                    document.getElementById('fact_iva').textContent = totalIVA.toLocaleString();
                    document.getElementById('fact_total').textContent = totalFacturado.toLocaleString();
                    document.getElementById('fact_emitidas1').textContent = emitidas;
                    document.getElementById('fact_pendientes1').textContent = pendientes;
                    document.getElementById('fact_pagadas1').textContent = pagadas;
                    document.getElementById('fact_anuladas1').textContent = anuladas;
                    document.getElementById('fact_neto1').textContent = totalNeto.toLocaleString();
                    document.getElementById('fact_iva1').textContent = totalIVA.toLocaleString();
                    document.getElementById('fact_total1').textContent = totalFacturado.toLocaleString();

                    // Gráfico
                    const ctx = document.getElementById('facturaChart').getContext('2d');
                    if (facturaChartInstance) facturaChartInstance.destroy();

                    facturaChartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Emitidas', 'Pend. Pago', 'Pagadas', 'Anuladas'],
                            datasets: [{
                                label: 'Cantidad',
                                data: [emitidas, pendientes, pagadas, anuladas],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 205, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(255, 99, 132, 0.2)'
                                ],
                                borderColor: [
                                    'rgb(54, 162, 235)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(255, 99, 132)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: Math.max(emitidas, pendientes, pagadas, anuladas) + 1,
                                    ticks: { stepSize: 1, precision: 0 }
                                }
                            }
                        }
                    });
                });
        }

        document.addEventListener("DOMContentLoaded", function () {
            flatpickr("#facturaDateRange", {
                mode: "range",
                dateFormat: "Y-m-d",
                onChange: function (selectedDates) {
                    if (selectedDates.length === 2) {
                        facturaStart = selectedDates[0].toISOString().split('T')[0];
                        facturaEnd = selectedDates[1].toISOString().split('T')[0];
                        loadFacturaKpis();
                    }
                }
            });

            document.getElementById("facturaQuickRange").addEventListener("change", function () {
                setFacturaRange(this.value);
            });

            // Carga inicial
            document.getElementById("facturaQuickRange").value = "last30";
            setFacturaRange("last30");
        });

        // Cargar gráfico de clientes, comparativo y facturado vs ingresos
        // Variables globales para los gráficos de clientes, comparativo y facturado vs ingresos

        flatpickr("#rango_clientes_fp", {
            mode: "range",
            dateFormat: "Y-m-d",
            locale: "es",
            onClose: function (selectedDates) {
                if (selectedDates.length === 2) {
                    facturaStart = selectedDates[0].toISOString().split("T")[0];
                    facturaEnd = selectedDates[1].toISOString().split("T")[0];
                    loadGraficoClientes();
                }
            }
        });
        document.getElementById('rango_clientes').addEventListener('change', function () {
            const value = this.value;
            const today = new Date();
            const start = new Date();

            if (value === 'custom') {
                document.getElementById('rango_clientes_fp').disabled = false;
                return;
            }

            document.getElementById('rango_clientes_fp').disabled = true;

            switch (value) {
                case 'last7':
                    start.setDate(today.getDate() - 6);
                    break;
                case 'last30':
                    start.setDate(today.getDate() - 29);
                    break;
                case 'last90':
                    start.setDate(today.getDate() - 89);
                    break;
                case 'thisYear':
                    start.setMonth(0, 1);
                    break;
            }

            facturaStart = start.toISOString().split("T")[0];
            facturaEnd = today.toISOString().split("T")[0];

            loadGraficoClientes();
        });
        function loadGraficoClientes() {
            const params = new URLSearchParams({ inicio: facturaStart, fin: facturaEnd });

            fetch(`/facturas/por-cliente?${params.toString()}`)
                .then(res => res.json())
                .then(data => {
                    const labels = data.map(d => d.cliente);
                    const valores = data.map(d => d.total_facturado);

                    const ctx = document.getElementById('graficoClientes').getContext('2d');
                    if (window.clienteChart) window.clienteChart.destroy();

                    window.clienteChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Total Facturado',
                                data: valores,
                                backgroundColor: labels.map(() => `hsl(${Math.random() * 360}, 60%, 70%)`)
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                });
        }
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('rango_clientes').value = 'last90';
            document.getElementById('rango_clientes').dispatchEvent(new Event('change'));
        });
    function loadGraficoComparativo() {
        fetch(`/facturas/comparativo-anual`)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('graficoComparativo').getContext('2d');
                if (window.comparativoChart) window.comparativoChart.destroy();

                window.comparativoChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: Object.entries(data.datasets).map(([label, valores], i) => ({
                            label,
                            data: valores,
                            borderColor: i === 0 ? 'rgba(255,99,132,1)' : 'rgba(54,162,235,1)',
                            backgroundColor: 'transparent',
                            tension: 0.2
                        }))
                    }
                });
            });
    }

    function loadGraficoFacturadoVsIngresos() {
        fetch('/facturas/facturado-vs-ingresos')
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('graficoFacturadoIngresos').getContext('2d');
                if (window.facturadoIngresosChart) window.facturadoIngresosChart.destroy();

                window.facturadoIngresosChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Total Facturado',
                                data: data.facturado,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)'
                            },
                            {
                                label: 'Ingresos (Pagadas)',
                                data: data.ingresos,
                                backgroundColor: 'rgba(75, 192, 192, 0.5)'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            });
    }

    document.addEventListener("DOMContentLoaded", function () {
        loadGraficoClientes();
        loadGraficoComparativo();
        loadGraficoFacturadoVsIngresos();
    });
    </script>
@endsection