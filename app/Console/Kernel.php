<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Vous pouvez enregistrer ici vos commandes artisan
    ];

    /**
     * Définissez la planification des tâches de l'application.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new \App\Jobs\SendEventReminder)->everyMinute();
    }

    /**
     * Enregistrez les commandes de l'application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
