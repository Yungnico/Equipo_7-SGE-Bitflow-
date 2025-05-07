<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;

class CotizacionDetallesSeeder extends Seeder
{
    public function run(): void
    {
        $cotizaciones = Cotizacion::all();

        foreach ($cotizaciones as $cotizacion) {
            CotizacionDetalle::create([
                'id_cotizacion'     => $cotizacion->id_cotizacion, // o $cotizacion->id si usas el default
                'estado'            => $cotizacion->estado,
                'motivo'            => null,
                'archivo'           => null,
                'factura_asociada'  => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        $this->command->info("Se migraron los estados actuales de cotizaciones a cotizacion_detalles.");
    }
}
