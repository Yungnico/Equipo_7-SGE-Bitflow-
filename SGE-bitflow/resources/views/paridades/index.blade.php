@extends('adminlte::page')

@section('title', 'Paridades')

@section('content_header')
    <h1>Paridades Registradas</h1>
@stop

@section('content')
    @if(session('success'))
        <x-adminlte-alert theme="success" title="Éxito">
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    @if($alerta)
        <x-adminlte-alert theme="warning" title="Alerta">
            {{ $alerta }}
        </x-adminlte-alert>
    @endif

    <x-adminlte-accordion id="acordionParidades" :items="[
        [
            'title' => 'Ver todas las paridades registradas',
            'content' => view('paridades.partials.lista', compact('paridades'))->render()
        ]
    ]" />
@stop
