@extends('adminlte::page')

@section('content')
<div class="card">
    <div class="card-header">Ajustar Paridad Manualmente</div>
    <div class="card-body">
        <form action="{{ route('paridades.guardarAjuste') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="valor">Nuevo Valor</label>
                <input type="number" step="0.0001" name="valor" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Guardar</button>
        </form>
    </div>
</div>
@endsection