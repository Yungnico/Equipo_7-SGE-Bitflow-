@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')

@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="py-5">
        <div class="card">
            <h2 class="p-3">Crear Usuario</h2>
    
            <div class="card-body">
    
    
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email">Correo</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
    
    
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

@endsection
