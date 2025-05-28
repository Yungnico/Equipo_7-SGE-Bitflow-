@extends('adminlte::page')

@section('title', 'Paridades')

@section('content_header')
    <h1>Paridades</h1>
@stop

@section('content')
    @if (session('success'))
        <x-adminlte-alert theme="success">{{ session('success') }}</x-adminlte-alert>
    @endif
    @if (session('warning'))
        <x-adminlte-alert theme="warning">{{ session('warning') }}</x-adminlte-alert>
    @endif
    @if (session('error'))
        <x-adminlte-alert theme="danger">{{ session('error') }}</x-adminlte-alert>
    @endif

    <a href="{{ route('paridades.fetch') }}" class="btn btn-success mb-3">Actualizar</a>
    

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Moneda</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paridades as $grupo)
                @php
                    $p = $grupo->first(); // Tomamos el primer registro de cada grupo
                @endphp
                <tr>
                    <td>{{ $p->moneda }}</td>
                    <td>${{ number_format($p->valor, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('paridades.edit', $p) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit" style="color: white"></i></a>
                    </td>
                </tr>
            @endforeach
            @if ($paridades->isEmpty())
                <tr>
                    <td colspan="4" class="text-center">No hay paridades registradas.</td>
                </tr>
            @endif

            
        </tbody>
    </table>
@stop



