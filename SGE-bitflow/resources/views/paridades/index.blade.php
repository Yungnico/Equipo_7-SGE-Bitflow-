<!-- resources/views/paridades/index.blade.php -->
@extends('adminlte::page')

@section('title', 'Paridades')

@section('content_header')
    <h1>Módulo de Paridades</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- 1. Ingreso Manual de Paridades -->
    <div class="card">
        <div class="card-header bg-success text-white">Ingreso manual</div>
        <div class="card-body">
            <form action="{{ route('paridades.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="moneda">Tipo de moneda</label>
                        <select class="form-control" name="moneda" required>
                            <option value="UF">UF</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="valor">Valor en CLP</label>
                        <input type="number" step="0.01" name="valor" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Guardar tasa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Tabla de Paridades Registradas -->
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">Paridades registradas</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-bs-toggle="collapse" data-bs-target="#paridadesTable" aria-expanded="false" aria-controls="paridadesTable">
                    <i class="fas fa-minus"></i> <!-- ícono de colapsar -->
                </button>
            </div>
        </div>
        <div id="paridadesTable" class="collapse show">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Moneda</th>
                            <th>Valor</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paridades as $paridad)
                            <tr>
                                <td>{{ $paridad->id }}</td>
                                <td>{{ $paridad->moneda }}</td>
                                <td>{{ $paridad->valor }}</td>
                                <td>{{ $paridad->fecha }}</td>
                                <td>
                                    <a href="{{ route('paridades.edit', $paridad->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('paridades.destroy', $paridad->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- 3. Calculadora de Conversión -->
    <div class="card mt-4">
        <div class="card-header bg-success text-white">Calculadora de conversión</div>
        <div class="card-body">
            <form action="{{ route('paridades.convertir') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label>Monto</label>
                        <input type="number" step="0.01" name="monto" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Moneda de origen</label>
                        <select name="moneda" class="form-control" required>
                            <option value="UF">UF</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Convertir</button>
                </div>
            </form>
            @if(session('resultado'))
                <div class="alert alert-info mt-3">
                    Resultado: {{ session('resultado') }} CLP
                </div>
            @endif
        </div>
    </div>

    <!-- 4. Ajuste Manual de Paridades -->
    <div class="card mt-4">
        <div class="card-header bg-success text-white">Ajuste manual</div>
        <div class="card-body">
            <form action="{{ route('paridades.ajustar') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label>Moneda</label>
                        <select name="moneda" class="form-control" required>
                            <option value="UF">UF</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Nuevo valor</label>
                        <input type="number" step="0.01" name="valor" class="form-control" required>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label>Motivo del ajuste (opcional)</label>
                    <input type="text" name="motivo" class="form-control">
                </div>
                <button type="submit" class="btn btn-success mt-2 text-white">Aplicar ajuste</button>
            </form>
        </div>
    </div>

    <!-- 5. Alertas de Valores Anómalos -->
    @if($alerta)
    <div class="card mt-4">
        <div class="card-header bg-danger text-white">Alerta de valores anómalos</div>
        <div class="card-body">
            <div class="alert alert-danger">
                ⚠️ {{ $alerta }}
            </div>
        </div>
    </div>
    @endif
</div>
@stop
