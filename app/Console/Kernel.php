<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define os comandos Artisan agendados.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Reminder diário de requisições que expiram amanhã
        $schedule->command('requisicoes:reminder')->dailyAt('08:00');
        //$schedule->command('carrinhos:check-abandoned')->everyFiveMinutes();
        $schedule->command('carrinhos:check-abandoned')->everyMinute();


    }

    /**
     * Regista os comandos Artisan disponíveis para o aplicativo.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__);

        require base_path('routes/console.php');

    }
    protected $commands = [
    \App\Console\Commands\TestGoogleBooks::class,
     \App\Console\Commands\CheckAbandonedCarrinhos::class,
];


}
