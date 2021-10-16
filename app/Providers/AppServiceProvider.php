<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(
            '*',
            static function ($view) {
                $currentlyPlaying = Cache::get(
                    sprintf('user.%d.currently-playing', Auth::id())
                );

                View::share('currentlyPlaying', $currentlyPlaying);
            }
        );
    }
}
