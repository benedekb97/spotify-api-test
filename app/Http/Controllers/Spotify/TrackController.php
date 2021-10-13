<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Entities\Spotify\PlaybackInterface;
use App\Entities\Spotify\Track;
use App\Entities\Spotify\TrackInterface;
use App\Http\Controllers\Controller;
use App\Repositories\PlaybackRepositoryInterface;
use App\Services\Providers\TrackStatisticsProvider;
use App\Services\Providers\TrackStatisticsProviderInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Collection;

class TrackController extends Controller
{
    private TrackStatisticsProviderInterface $trackStatisticsProvider;

    public function __construct(
        EntityManager $entityManager,
        TrackStatisticsProvider $trackStatisticsProvider
    )
    {
        $this->trackStatisticsProvider = $trackStatisticsProvider;

        parent::__construct($entityManager);
    }

    public function show(string $track)
    {
        /** @var TrackInterface $track */
        $track = $this->findOr404(Track::class, $track);

        $header = [['Date', 'Playbacks']];

        $playbacks = $this->trackStatisticsProvider->provideForUser($track, $this->getUser());

        $playbacks = array_merge($header, $playbacks);

        return view(
            'pages.spotify.tracks.show',
            [
                'track' => $track,
                'playbacks' => $playbacks
            ]
        );
    }
}
