<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicio1 = \App\Models\Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 1',
            'descripcion' => 'Descripción del servicio de prueba 1',
            'precio' => 10000,
            'moneda' => 'CLP',
        ]);
        $servicio2 = \App\Models\Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 2',
            'descripcion' => 'Descripción del servicio de prueba 2',
            'precio' => 20000,
            'moneda' => 'CLP',
        ]);
        $servicio3 = \App\Models\Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 3',
            'descripcion' => 'Descripción del servicio de prueba 3',
            'precio' => 30000,
            'moneda' => 'USD',
        ]);
        $servicio4 = \App\Models\Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 4',
            'descripcion' => 'Descripción del servicio de prueba 4',
            'precio' => 40000,
            'moneda' => 'UF',
        ]);

    }
}
