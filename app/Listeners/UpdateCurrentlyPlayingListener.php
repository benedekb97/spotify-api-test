<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Spotify\TrackInterface;
use App\Http\Api\Events\UpdateCurrentlyPlayingEvent;
use App\Services\Providers\Spotify\TrackProvider;
use App\Services\Providers\Spotify\TrackProviderInterface;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\Log;

class UpdateCurrentlyPlayingListener
{
    private TrackProviderInterface $trackProvider;

    private Repository $cache;

    public function __construct(
        TrackProvider $trackProvider,
        Repository $cache
    ) {
        $this->trackProvider = $trackProvider;
        $this->cache = $cache;
    }

    public function handle(UpdateCurrentlyPlayingEvent $event): void
    {
        if ($event->getTrack() === null) {
            return;
        }

        $user = $event->getUser();

        $track = $this->trackProvider->provide($event->getTrack());

        $this->cache->forget($key = sprintf('user.%s.currently-playing', $user->getId()));
        $this->cache->rememberForever(
            $key,
            static function () use ($track): TrackInterface
            {
                return $track;
            }
        );
    }
}
