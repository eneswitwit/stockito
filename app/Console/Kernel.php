<?php

namespace App\Console;

use App\Console\Commands\CheckFTPUsersCommand;
use App\Console\Commands\CheckMediaCommand;
use App\Console\Commands\ClearProcessingCommand;
use App\Console\Commands\CreateAdminCommand;
use App\Console\Commands\CheckThumbnailsCommand;
use App\Console\Commands\HandleFTPFilesCommand;
use App\Console\Commands\RemoveProductsCommand;
use App\Console\Commands\SyncProductsCommand;
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
        CreateAdminCommand::class,
        SyncProductsCommand::class,
        RemoveProductsCommand::class,
        CheckThumbnailsCommand::class,
        CheckMediaCommand::class,
        CheckFTPUsersCommand::class,
        HandleFTPFilesCommand::class,
        ClearProcessingCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
