<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Programación automática de paridades
        $schedule->command('paridades:registrar-mensuales')->dailyAt('01:00');

        // Otros comandos programados pueden ir aquí...
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\ObtenerParidadesMindicador::class,
        \App\Console\Commands\RegistrarParidadesMensuales::class, // ← Asegúrate de registrar también este
    ];
}


