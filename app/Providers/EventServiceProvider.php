<?php

namespace App\Providers;

use App\Http\Api\Events\CreatePlaybackEvent;
use App\Http\Api\Events\UpdatePlaylistCoverEvent;
use App\Http\Api\Events\UpdatePlaylistsEvent;
use App\Http\Api\Events\UpdatePlaylistTracksEvent;
use App\Http\Api\Events\UpdateProfileEvent;
use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Events\UpdateUserTracksEvent;
use App\Listeners\CreatePlaybackListener;
use App\Listeners\UpdatePlaylistCoverListener;
use App\Listeners\UpdatePlaylistsListener;
use App\Listeners\UpdatePlaylistTracksListener;
use App\Listeners\UpdateProfileListener;
use App\Listeners\UpdateTracksListener;
use App\Listeners\UpdateUserTracksListener;
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
        UpdatePlaylistCoverEvent::class => [
            UpdatePlaylistCoverListener::class,
        ],
        UpdateProfileEvent::class => [
            UpdateProfileListener::class,
        ],
        UpdateUserTracksEvent::class => [
            UpdateUserTracksListener::class,
        ]
    ];

    public function boot()
    {
        //
    }
}
