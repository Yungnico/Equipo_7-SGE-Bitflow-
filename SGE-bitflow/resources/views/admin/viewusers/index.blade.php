@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Lista de usuario</h1>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">
@stop

@section('content')
    <div class="card">
        <div class="card-body">


            <table class="table table-striped table-bordered " id="usuarios" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td width="10px"><a class="btn btn-primary" href="{{route('viewusers.edit', $user)}}"><i class="fas fa-edit"></i></a></td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>    

@stop


@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#usuarios').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/2.3.0/i18n/es-CL.json',
                }
            });

            // Redibujar la tabla al cambiar el tamaño de la ventana
            $(window).on('resize', function() {
                table.columns.adjust().responsive.recalc();
            });
        });
    </script>
@stop