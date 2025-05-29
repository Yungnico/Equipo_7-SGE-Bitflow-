<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Paridad;

class ObtenerParidadesMindicador extends Command
{
    protected $signature = 'paridades:importar';
    protected $description = 'Importa paridades desde la API mindicador.cl';

    public function handle()
    {
        $monedas = ['dolar', 'euro', 'uf'];

        foreach ($monedas as $moneda) {
            $response = Http::get("https://mindicador.cl/api/{$moneda}");

            if ($response->successful()) {
                $data = $response->json();
                $serie = $data['serie'][0] ?? null;

                if ($serie) {
                    Paridad::updateOrCreate(
                        ['moneda' => strtoupper($moneda), 'fecha' => date('Y-m-d', strtotime($serie['fecha']))],
                        ['valor' => $serie['valor']]
                    );
                }
            }
        }

        $this->info('Paridades actualizadas correctamente.');
    }
}
