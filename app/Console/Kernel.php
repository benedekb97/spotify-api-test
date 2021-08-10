<?php

namespace App\Console;

use App\Jobs\CreateWeeklyMostPlayedPlaylistJob;
use App\Jobs\GetRecentlyPlayedJob;
use App\Jobs\SynchronizePlaylistsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(GetRecentlyPlayedJob::class)->everyThirtyMinutes();
        $schedule->call(CreateWeeklyMostPlayedPlaylistJob::class)->weekly();
//        $schedule->call(SynchronizePlaylistsJob::class)->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
