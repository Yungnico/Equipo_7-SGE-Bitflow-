@extends('adminlte::page')

@section('title', 'Editar Paridad')

@section('content_header')
    <h1>Editar Paridad {{ $paridad->moneda }} - {{ \Carbon\Carbon::parse($paridad->fecha)->format('d/m/Y') }}</h1>
@stop

@section('content')

@if (session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('paridades.update', $paridad) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="valor">Valor</label>
        <input type="number" step="0.01" name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" 
               value="{{ old('valor', $paridad->valor) }}" required>
        @error('valor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
    <a href="{{ route('paridades.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
</form>

@stop

@section('js')
<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 7000);
</script>
@stop
