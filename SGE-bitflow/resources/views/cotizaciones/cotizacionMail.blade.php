@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('content')
<!DOCTYPE html>
<div class="container pt-4">
    <div class="card">
        <div class="card-body">
            <div class="email-window bg-white text-dark">
                <div class="email-header">
                    <strong>Enviar Cotizacion N°: {{$cotizacion->codigo_cotizacion}}</strong>
                </div>
                <form action="{{ route('cotizaciones.enviar', $cotizacion->id_cotizacion) }}" method="POST">
                    @csrf
                    <div class="email-body">
                        <div class="mb-3">
                            <input type="email" class="form-control email-input" placeholder="" name="correo_destino" value="{{$cotizacion->email}}">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control email-input" placeholder="Asunto" name="asunto">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje</label>
                            <textarea class="form-control" rows="10" placeholder="Escribe tu mensaje..." name="mensaje"></textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="1" id="adjuntarPdf" name="adjuntarPdf">
                            <label class="form-check-label" for="adjuntarPdf">
                                ¿Desea agregar en el email el PDF de la cotización?
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
{{-- 
⣿⣟⣿⢻⡽⣫⢟⡭⢯⡙⢧⢛⡬⢣⡙⡜⡘⢆⠳⢌⠒⠥⠚⠤⢓⡘⠦⡙⢢⡙⢬⠣⡝⢮⡹⣍⠯⣝⢯⡻⣽⢻⣟⡿⣿
⣟⡾⣭⢗⡯⢳⡍⡞⢥⠛⣌⠲⢌⠡⠒⠌⡑⡈⠢⠌⡘⠠⢉⠂⠡⢈⠔⡁⢃⠜⡠⢓⡘⠦⡑⢎⠳⢎⡳⡝⣮⢻⡼⣻⡽
⣯⢳⣝⠺⣜⢣⢚⡱⢊⠱⣀⠣⠌⠂⡉⠐⠠⢀⡡⠔⠀⠁⠀⠈⠐⠀⠤⡐⠀⠂⠄⡡⠈⢆⠩⢌⠣⣍⠲⣙⢖⡫⣞⡵⣻
⢧⡏⣜⠳⡌⢆⠣⡐⠡⠒⠠⠐⢠⠞⠀⠁⢰⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⢳⠈⠀⠙⣆⠠⠈⠄⢃⠄⠳⠌⣎⠱⣎⢼⢳
⡳⡜⣌⠓⡌⠢⠑⠠⠁⠂⠁⠀⢾⠀⠀⠀⣸⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⡀⠀⠀⢸⠂⠈⠐⠈⡈⢡⠊⡔⠣⡜⣊⠗
⡱⡘⡄⢣⠈⢡⠃⠁⠀⠀⠀⠀⢸⠀⠀⠠⡇⠀⠀⠀⠀⢤⠀⢠⡄⠀⠀⠀⠀⣧⠀⠀⢸⠀⠀⠀⠐⠀⠄⢣⢈⠱⡘⢤⠛
⢡⡑⠌⡠⠈⢼⠀⠀⠀⠀⠀⠀⢿⡀⠀⠀⢳⠀⠀⠀⢀⡾⠀⠘⣇⠀⠀⠀⢠⡇⠀⠀⢨⠇⠀⠀⠀⠀⠀⣸⠀⢂⠱⡈⡍
⢂⠌⡐⠀⠄⠈⢧⠀⠀⠀⠀⠀⠈⢳⡀⠀⠘⠲⣄⠀⢈⢳⣤⣠⣋⡀⠀⡠⠞⠁⠀⢠⠋⠀⠀⠀⠀⠀⣰⠋⠀⠂⠄⡑⠌
⢂⠐⡀⡁⠀⠀⠈⣧⠀⠀⠀⠀⠀⠈⠣⠤⢤⣀⡈⢲⣿⣿⣿⣿⣿⣿⡎⣀⡠⠤⠤⠟⠀⠀⠀⠀⠀⢠⡇⠀⠀⠀⡂⠄⡉
⢀⠂⡜⠀⠀⠀⠀⠙⠛⠒⠦⣤⣀⠀⣀⣀⣀⡤⠬⠽⣿⣿⣿⣿⣿⣿⠯⠤⠤⣤⣀⣀⣀⣠⠴⠒⠛⠋⠁⠀⠀⠀⠘⡄⡐
⠀⢰⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠙⠋⠉⠀⠀⠀⣠⣶⣾⣿⣿⣿⣿⣷⣦⣄⠀⠀⠀⠉⠉⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⡷⠀
⠀⡀⢳⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⡤⠖⠚⣽⣿⣿⣿⣿⢿⡿⣿⣿⣿⣿⣏⠑⠲⢤⣀⡀⠀⠀⠀⠀⠀⠀⠀⠀⣴⠃⠀
⠀⠀⠈⣧⣀⣠⣤⢤⣤⣤⣠⡶⠛⠁⠀⠀⣸⣿⣿⣿⣿⣅⣿⡿⢸⣿⣿⣿⣿⣆⠀⠀⠈⠙⠶⣤⡤⠤⠤⠤⢤⣴⡇⠀⠀
⠀⠀⠀⠈⠉⠀⠀⠀⠀⠀⠁⠀⠀⢀⣰⠎⢹⣿⣿⣿⣿⣿⡿⣱⣿⣿⣿⣿⣿⠇⠱⢆⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠂⠀⠀⠀⠀⠀⠀⠀⠀⢀⡶⠛⠉⠀⠀⢨⢿⣿⣿⣿⣟⣚⣛⣳⣿⣿⣿⠿⣄⠀⠀⠈⠙⢶⡀⠀⠀⠀⠀⠀⠀⠀⠀⢁
⠀⠄⠀⠀⠀⠀⠀⠀⠀⣠⠟⠀⠀⠀⣠⡶⠃⠀⠙⢿⣿⣿⣿⣿⣿⣿⠟⠁⠀⠈⢷⣄⠀⠀⠀⠹⣆⠀⠀⠀⠀⠀⠀⠀⠌
⠀⠄⠀⠀⠀⠀⣀⣴⠞⠁⠀⠀⠀⠰⡏⠀⠀⠀⠀⠀⠉⠛⠿⠟⠛⠁⠀⠀⠀⠀⠀⣽⠀⠀⠀⠀⠈⠻⣦⡄⠀⠀⠀⠐⡀
⠐⡀⠂⠀⠀⠀⢿⡁⠀⠀⠀⠀⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⠀⠀⠀⠀⠀⠀⢸⠇⠀⠀⠀⢂⠐
⡐⢀⠂⢀⠀⠀⠈⣧⠀⠀⠀⠀⠀⢠⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⡀⠀⠀⠀⠀⠀⡟⠀⠀⠀⡈⠄⡌
⢄⠃⠌⡀⢀⠀⠀⢸⠀⠀⠀⠀⠀⣼⠇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⡧⠀⠀⠀⠀⢠⡇⠀⠀⡐⢀⠒⡌
⠬⣘⠰⢀⠂⠠⠀⢸⡆⠀⠀⠀⠀⠘⢷⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡾⠁⠀⠀⠀⠀⣸⠇⠀⡐⢠⠊⡔⠬
⠳⣄⠣⢌⢂⠡⠐⠀⠙⠦⣄⡀⠀⠀⠈⢳⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡟⠀⠀⠀⢀⡠⠞⠁⡀⢂⠔⡡⢊⣌⠳
⡳⣌⠳⣌⠢⡑⢌⡐⠠⢀⠀⠀⠀⠀⠀⠀⢷⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡞⠀⠀⠀⠀⢀⠠⠐⡀⢆⠡⢊⡔⢣⣌⢻
⢷⣩⠳⣌⠳⡘⢤⠂⡅⢂⠌⡐⠠⢀⠀⠀⠈⠳⣄⠀⠀⠀⠀⠀⠀⠀⠀⣠⠞⠀⠀⡀⠄⠂⠄⡂⠥⡘⢤⡙⢆⡺⡱⢎⡷
⡿⣜⡻⣬⢳⡙⢦⡙⢤⢃⡒⠤⣁⠂⢌⠐⠠⢀⠀⡉⢂⠀⠄⠠⢀⠐⡉⢀⠠⢀⠡⡐⢨⠘⠤⣑⢢⡙⠦⣜⢣⡳⣝⢯⣞
⣿⣝⣳⣭⡳⣝⠶⣙⢦⢣⡜⠲⢤⡉⢆⡌⡑⢂⠥⡐⠤⡈⠤⣁⠢⡐⡐⢢⠘⡄⢣⡘⢤⢋⠖⣡⠖⣭⣓⢮⡳⣝⣾⣫⢾
⣿⣞⡷⣯⢷⣫⢟⡭⣞⡱⢮⣙⢦⡙⢦⡘⢥⢃⠦⡑⢦⡑⠲⣄⠣⡔⡱⢂⡳⢌⡣⡜⢦⣋⡞⣥⣛⢶⣹⢮⣟⡽⣶⣻⣿ --}}