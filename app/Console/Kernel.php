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
        Commands\TestBatch::class,
        Commands\SendEmailInvitation::class,
        Commands\CheckEventTable::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule
        //     ->command('emailinvitation:send')
        //     ->withoutOverlapping()
        //     //->everyMinute()
        //     ->everyFiveMinutes()
        //     //->everyThirtyMinutes()
        //     // ->runInBackground()
        // ;
        $schedule->command('events:check')->everyThirtyMinutes();
        $schedule->command('report:admin')->weeklyOn(1, '11:56');
               
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
