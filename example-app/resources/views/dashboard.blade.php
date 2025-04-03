<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    @extends('layouts.app')

    @section('content')
    <div class="container">
        <h1>Dashboard</h1>

        <a href="{{ route('products.index') }}" class="btn btn-primary">Administrar Productos</a>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Ir al CRUD de Productos</a>

    </div>
    @endsection

</x-app-layout>
