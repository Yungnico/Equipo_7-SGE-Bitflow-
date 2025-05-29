<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Cliente::firstOrCreate([
            'razon_social' => 'Cliente de Prueba',
            'rut' => '12345678-9',
            'direccion' => 'Dirección de prueba',
            'nombre_fantasia' => 'Cliente Fantasía',
            'giro' => 'Giro de prueba',
            'plazo_pago_habil_dias' => 30,
        ]);

        \App\Models\Cliente::firstOrCreate([
            'razon_social' => 'Cliente de Prueba 2',
            'rut' => '98765432-1',
            'direccion' => 'Dirección de prueba 2',
            'nombre_fantasia' => 'Cliente Fantasía 2',
            'giro' => 'Giro de prueba 2',
            'plazo_pago_habil_dias' => 30,
        ]);

        \App\Models\Cliente::firstOrCreate([
            'razon_social' => 'Cliente de Prueba 3',
            'rut' => '11111111-1',
            'direccion' => 'Dirección de prueba 3',
            'nombre_fantasia' => 'Cliente Fantasía 3',
            'giro' => 'Giro de prueba 3',
            'plazo_pago_habil_dias' => 30,
        ]);
    }
}
