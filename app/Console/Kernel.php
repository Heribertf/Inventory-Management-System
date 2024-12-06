<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UpdateInventoryFromSage;
use App\Console\Commands\UpdateInventoryFromSageMds;
use App\Console\Commands\UpdateInventoryFromSageDs;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        UpdateInventoryFromSage::class,
        UpdateInventoryFromSageMds::class,
        UpdateInventoryFromSageDs::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inventory:syncRecords')->hourly();
        $schedule->command('inventory:sync_mds_records')->daily()->timezone('Africa/Nairobi')->appendOutputTo(storage_path('logs/inventory.log'));
        $schedule->command('inventory:sync_ds_records')->dailyAt('01:00')->timezone('Africa/Nairobi')->appendOutputTo(storage_path('logs/inventory.log'));

        $schedule->command('projects:updateDaysLeft')->daily()->timezone('Africa/Nairobi');
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
