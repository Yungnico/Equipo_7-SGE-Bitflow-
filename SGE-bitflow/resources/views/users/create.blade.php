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
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-lg-11 ">
    
                <div class="card mb-4 ">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-11 col-md-11 col-lg-11 col-xl-11">
                                <h2 class="mb-1">Crear usuario</h2>
                                <hr class="mb-4 border">
                                <form action="{{ route('users.store') }}" method="POST" id="registrar_usuario">
                                    @csrf
                                    
                                        <label for="name">Nombre</label>
                                        <input type="text" name="name" class="form-control mb-3 border border-dark" required value="{{ old('name') }}">
                                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
    
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control mb-3 border border-dark" required value="{{ old('email') }}">
                                        @error('email')<small class="text-danger">{{ $message }}</small>@enderror
    
                                        <label for="email_confirmation">Confirmar Email</label>
                                        <input type="email" name="email_confirmation" class="form-control mb-3 border border-dark" required value="{{ old('email_confirmation') }}">
                                        @error('email_confirmation')<small class="text-danger">{{ $message }}</small>@enderror
    
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label>Asignar Roles</label>
                                            <div >
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
    
                                    <div class="row pb-3">
                                        <div class="col-md-12 text-end">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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