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
        $schedule->call(GetRecentlyPlayedJob::class)->everyFiveMinutes();
        $schedule->call(CreateWeeklyMostPlayedPlaylistJob::class)->sundays()->at('22:00');
        $schedule->call(SynchronizePlaylistsJob::class)->everyThirtyMinutes();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
