@vite(['resources/css/app.css', 'resources/js/app.js'])
@extends('adminlte::page')
@section('content')
    
    <div class="mt-3 p-4 sm:p-8 bg-gray dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-2">
            @include('profile.partials.update-password-form')
        </div>
    </div>
    
@endsection
