@extends('adminlte::page')

@section('title', 'Editar Paridad')

@section('content_header')
    <h1>Editar Paridad</h1>
@endsection

@section('content')
    <form action="{{ route('paridades.update', $paridad->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="moneda" class="form-label">Moneda</label>
            <input type="text" name="moneda" class="form-control" value="{{ $paridad->moneda }}" required>
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <input type="number" step="0.0001" name="valor" class="form-control" value="{{ $paridad->valor }}" required>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" value="{{ $paridad->fecha }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('paridades.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
