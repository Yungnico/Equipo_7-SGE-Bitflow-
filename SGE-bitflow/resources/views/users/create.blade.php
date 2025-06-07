@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')

@endsection

@section('content')
    <!--
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    -->
    <div class="py-5">
        <div class="card">
            <h2 class="p-3">Crear Usuario</h2>
    
            <div class="card-body">
    
    
                <form action="{{ route('users.store') }}" method="POST" id="registrar_usuario">
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
                            <label for="email_confirmation">Confirmar Correo</label>
                            <input type="email" name="email_confirmation" class="form-control" required value="{{ old('email_confirmation') }}">
                            @error('email_confirmation')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
    
                    <div class="row mb-3">
    <div class="col-md-12">
        <label>Asignar Roles</label>
        <div class="d-flex flex-wrap gap-3">
            @foreach($roles as $role)
                <div class="form-check">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        class="form-check-input" id="role_{{ $role->id }}"
                        {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                    <label for="role_{{ $role->id }}" class="form-check-label">
                        {{ ucfirst($role->name) }}
                    </label>
                </div>
            @endforeach
        </div>
        @error('roles')<small class="text-danger">{{ $message }}</small>@enderror
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
Swal.fire({
    title: "Creado exitosamente!",
    text: "{{ session('success') }}",
    icon: "success",
    confirmButtonColor: "#3085d6"
});
</script>
@endif
@endsection 