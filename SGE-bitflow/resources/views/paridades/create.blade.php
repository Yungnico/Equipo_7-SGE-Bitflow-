@extends('adminlte::page')

@section('title', 'Agregar Paridad')

@section('content_header')
    <h1>Agregar Nueva Paridad</h1>
@endsection

@section('content')
    <form action="{{ route('paridades.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="moneda" class="form-label">Moneda</label>
            <input type="text" name="moneda" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <input type="number" step="0.0001" name="valor" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('paridades.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
