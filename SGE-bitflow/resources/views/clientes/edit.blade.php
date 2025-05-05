@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Editar Cliente</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('clientes.update', $cliente) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('clientes.form')
    </form>
</div>
@endsection
