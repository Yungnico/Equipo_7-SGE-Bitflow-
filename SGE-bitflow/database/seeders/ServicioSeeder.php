<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paridad;
use App\Models\Servicio;
use Carbon\Carbon;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        // Crear monedas
        Paridad::create([
            'moneda' => 'USD',
            'valor' => 800.00,
            'fecha' => now(),
        ]);

        Paridad::create([
            'moneda' => 'UF',
            'valor' => 3000.00,
            'fecha' => now(),
        ]);

        Paridad::create([
            'moneda' => 'CLP',
            'valor' => 1.00,
            'fecha' => now(),
        ]);

        // Crear servicios con categorías
        Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 1',
            'descripcion' => 'Descripción del servicio de prueba 1',
            'precio' => 10000,
            'moneda_id' => 1,
            'categoria_id' => 1, // categoria1
        ]);

        Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 2',
            'descripcion' => 'Descripción del servicio de prueba 2',
            'precio' => 20000,
            'moneda_id' => 1,
            'categoria_id' => 2, // categoria2
        ]);

        Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 3',
            'descripcion' => 'Descripción del servicio de prueba 3',
            'precio' => 30000,
            'moneda_id' => 2,
            'categoria_id' => 3, // categoria3
        ]);

        Servicio::create([
            'nombre_servicio' => 'Servicio de Prueba 4',
            'descripcion' => 'Descripción del servicio de prueba 4',
            'precio' => 40000,
            'moneda_id' => 3,
            'categoria_id' => 4, // categoria4
        ]);
    }
}
