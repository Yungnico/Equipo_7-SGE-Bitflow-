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
        //como llamo a la api para obtener las monedas y paridades
        // Crear algunas monedas de ejemplo
        \App\Models\Paridad::create([
            'moneda' => 'USD',
            'valor' => 800.00,
            'fecha' => now(),
        ]);
        \App\Models\Paridad::create([
            'moneda' => 'UF',
            'valor' => 3000.00,
            'fecha' => now(),
        ]);
        \App\Models\Paridad::create([
            'moneda' => 'CLP',
            'valor' => 1.00,
            'fecha' => now(),
        ]);

        $servicio1 = \App\Models\Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 1',
            'descripcion' => 'Descripción del servicio de prueba 1',
            'precio' => 10000,
            'moneda_id' => 1,
        ]);
        $servicio2 = \App\Models\Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 2',
            'descripcion' => 'Descripción del servicio de prueba 2',
            'precio' => 20000,
            'moneda_id' => 1,
        ]);
        $servicio3 = \App\Models\Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 3',
            'descripcion' => 'Descripción del servicio de prueba 3',
            'precio' => 30000,
            'moneda_id' => 2,
        ]);
        $servicio4 = \App\Models\Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 4',
            'descripcion' => 'Descripción del servicio de prueba 4',
            'precio' => 40000,
            'moneda_id' => 3,
        ]);

    }
}
