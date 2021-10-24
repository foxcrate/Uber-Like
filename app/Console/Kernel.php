<?php

namespace App\Console;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\CustomCommand::class,
        \App\Console\Commands\CreatedMRes::class,
        \App\Console\Commands\CreatedYRes::class,
        \App\Console\Commands\CheckCars::class,
        \App\Console\Commands\finshedPro::class,
        Commands\otpExpire::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('update:rides')
        //         ->everyMinute();

        $schedule->command('otp:expireAll')
                ->everyMinute();

       // $schedule->command('command:CreatedMRes')->everyMinute();
       /* $schedule->call(function () {

            })->daily();*/
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
