<?php

namespace App\Providers;

use App\Http\Api\Events\CreatePlaybackEvent;
use App\Http\Api\Events\UpdatePlaylistsEvent;
use App\Http\Api\Events\UpdatePlaylistTracksEvent;
use App\Http\Api\Events\UpdateTracksEvent;
use App\Listeners\CreatePlaybackListener;
use App\Listeners\UpdatePlaylistsListener;
use App\Listeners\UpdatePlaylistTracksListener;
use App\Listeners\UpdateTracksListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UpdateTracksEvent::class => [
            UpdateTracksListener::class,
        ],
        CreatePlaybackEvent::class => [
            CreatePlaybackListener::class,
        ],
        UpdatePlaylistsEvent::class => [
            UpdatePlaylistsListener::class,
        ],
        UpdatePlaylistTracksEvent::class => [
            UpdatePlaylistTracksListener::class,
        ],
    ];

    public function boot()
    {
        //
    }
}
