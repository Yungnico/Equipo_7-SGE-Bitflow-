<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = \App\Models\Cliente::firstOrCreate([
            'razon_social' => 'Cliente de Prueba',
            'rut' => '12.345.678-9',
            'direccion' => 'Dirección de prueba',
            'nombre_fantasia' => 'Cliente Fantasía',
            'giro' => 'Giro de prueba',
            'direccion' => 'Dirección de prueba',]);
        $cliente2 = \App\Models\Cliente::firstOrCreate([
            'razon_social' => 'Cliente de Prueba 2',
            'rut' => '98.765.432-1',
            'direccion' => 'Dirección de prueba 2',
            'nombre_fantasia' => 'Cliente Fantasía 2',
            'giro' => 'Giro de prueba 2',
            'direccion' => 'Dirección de prueba 2',]);
        $cliente3 = \App\Models\Cliente::firstOrCreate([
            'razon_social' => 'Cliente de Prueba 3',
            'rut' => '11.111.111-1',
            'direccion' => 'Dirección de prueba 3',
            'nombre_fantasia' => 'Cliente Fantasía 3',
            'giro' => 'Giro de prueba 3',
            'direccion' => 'Dirección de prueba 3',]);
    }
        
}
