<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Paridad;
use Illuminate\Support\Facades\Http;

class RegistrarParidadesMensuales extends Command
{
    protected $signature = 'paridades:registrar-mensuales';
    protected $description = 'Registrar automáticamente paridades para todo el mes actual';

    public function handle()
    {
        $mesActual = now()->format('m');
        $anioActual = now()->format('Y');

        for ($dia = 1; $dia <= now()->daysInMonth; $dia++) {
            $fecha = "$anioActual-$mesActual-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
            $url = "https://mindicador.cl/api/dolar/$fecha";

            $response = Http::get($url);

            if ($response->successful()) {
                $datos = $response->json();
                if (isset($datos['serie'][0])) {
                    $valor = $datos['serie'][0]['valor'];

                    Paridad::updateOrCreate(
                        ['moneda' => 'USD', 'fecha' => $fecha],
                        ['valor' => $valor]
                    );
                }
            }

            sleep(1); // Evita sobrecarga de la API
        }

        $this->info('Paridades del mes registradas correctamente.');
    }
}

