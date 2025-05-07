@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($servicios->isEmpty())
<div class="alert alert-warning">No hay servicios registrados.</div>
@endif