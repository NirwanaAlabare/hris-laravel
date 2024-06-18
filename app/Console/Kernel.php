<?php

namespace App\Console;

use App\Services\schedule_absen;
use App\Console\Commands\AutoDbSeed;
use App\Console\Commands\VendorCleanUpCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\UpdateDTPC::class,
        Commands\UpdateStatusAbsen::class,
        Commands\UpdateAbsenLintasHari::class,
        Commands\UpdateTanggalResign::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dtpc')->everyFiveMinutes()->sendOutputTo(public_path().'/tasks/log.txt');
        $schedule->command('lintas_hari')->everyFiveMinutes()->sendOutputTo(public_path().'/tasks/log.txt');
        $schedule->command('statusAbsen')->monthlyOn(26, '06:00');
        $schedule->command('resign')->monthlyOn(26, '06:00');
  // =================== Andri ==========================
        //Rekap Absen
        // $schedule->call(function(){
        //     $test = (new  schedule_absen)->RekapAbsen();
        // })->everyMinute();

        // $schedule->call(function(){
        //     $test = (new  schedule_absen)->new_employee();
        // })->everyMinute();
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
