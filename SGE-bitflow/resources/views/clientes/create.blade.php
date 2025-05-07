@extends('adminlte::page')


@section('content')
<div class="container pt-3">	

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
        @include('clientes.form')
    </form>
</div>
@endsection
