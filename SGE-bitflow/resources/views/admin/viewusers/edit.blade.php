@extends('adminlte::page')

@section('title', 'Asignar rol')

@section('content_header')
    <h1>Asignar un rol</h1>
@stop

@section('css')
    
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success ">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif


    <div class="card">
        <div class="card-body">
            <p class="h5">Nombre:</p>
            <input type="text" class="form-control" value="{{ $user->name }}" readonly>

            <!-- Formulario sin Laravel Collective -->
            <form action="{{ route('viewusers.update', $user) }}" method="POST" class="mt-3">
                @csrf
                @method('PUT')

                @foreach ($roles as $role)
                    <div >
                        <label>
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mr-1"
                                {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                            {{ $role->name }}
                        </label>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary mt-3">Asignar Rol</button>
                <button type="button" class="btn btn-secondary mt-3" onclick="window.location='{{ route('viewusers.index') }}'">Regresar</button>
            </form>
        </div>
    </div>  
@stop

@section('js')
    
@stop

@section('css')
    @font-face {
        font-family: 'Gilb';
        src: url('/fonts/GILB____.TTF') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Inter';
        src: url('/fonts/Inter-VariableFont_slnt,wght.ttf') format('truetype');
        font-weight: 100 900;
        font-style: normal;
    }

    body {
        font-family: 'Inter', sans-serif;
    }
@stop 