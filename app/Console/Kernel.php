<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * These cron jobs are run in the background by the cron service on the server.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {

        // Run inventory checks daily at 9 AM
        $schedule->command('inventory:check-notifications')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Check inventory levels daily at 9 AM
        $schedule->command('inventory:check')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Generate purchase orders daily at 10 AM
        $schedule->command('inventory:generate-purchase-orders')
            ->dailyAt('10:00')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
