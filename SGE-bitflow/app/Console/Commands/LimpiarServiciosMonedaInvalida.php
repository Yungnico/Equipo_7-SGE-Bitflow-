<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LimpiarServiciosMonedaInvalida extends Command
{
    protected $signature = 'limpiar:monedas-invalidas';
    protected $description = 'Elimina registros en servicios con moneda_id que no existe en monedas';

    public function handle()
    {
        // Detectar registros inválidos
        $invalidos = DB::table('servicios')
            ->whereNotIn('moneda_id', function ($query) {
                $query->select('id')->from('monedas');
            })
            ->get();

        if ($invalidos->isEmpty()) {
            $this->info('No se encontraron registros con moneda_id inválido.');
            return 0;
        }

        // Mostrar cuántos registros se eliminarán
        $this->warn("Se eliminarán {$invalidos->count()} registros con moneda_id inválido.");

        if ($this->confirm('¿Deseas continuar y eliminar estos registros?')) {
            DB::table('servicios')
                ->whereNotIn('moneda_id', function ($query) {
                    $query->select('id')->from('monedas');
                })
                ->delete();

            $this->info('Registros inválidos eliminados correctamente.');
        } else {
            $this->info('Operación cancelada.');
        }

        return 0;
    }
}

