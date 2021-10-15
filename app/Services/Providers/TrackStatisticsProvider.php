<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;
use App\Repositories\PlaybackRepositoryInterface;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class TrackStatisticsProvider implements TrackStatisticsProviderInterface
{
    private PlaybackRepositoryInterface $playbackRepository;

    public function __construct(
        PlaybackRepositoryInterface $playbackRepository
    ) {
        $this->playbackRepository = $playbackRepository;
    }

    public function provideForUser(TrackInterface $track, UserInterface $user): array
    {
        $playbacks = $this->playbackRepository->getPlaybacksByTrackGroupedByDate($track, $user);

        if (empty($playbacks)) {
            $startDate = new DateTime('-1 day');
        } else {
            $startDate = new DateTime(Arr::first($playbacks)['playedAtDate']);
        }

        $endDate = new DateTime();

        $playbacks = (new Collection($playbacks))
            ->mapWithKeys(
                static function ($playback): array
                {
                    return [$playback['playedAtDate'] => $playback['count']];
                }
            )->toArray();

        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);

        $statistics = [];

        foreach ($period as $date) {
            $statistics[] = [$date->format('Y-m-d'), $playbacks[$date->format('Y-m-d')] ?? 0];
        }

        return $statistics;
    }
}
