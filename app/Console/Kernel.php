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
        $schedule->command('app:hourly-update')->hourly();
        $schedule->command('app:daily-update')->daily();
        $schedule->command('records:clear')->daily();
        $schedule->command('app:minute-update')->everyMinute();
        $schedule->command('app:update-call-status')->everyTwoMinutes();
        // $schedule->command('firewall:ip-block')->everyThirtySeconds();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
