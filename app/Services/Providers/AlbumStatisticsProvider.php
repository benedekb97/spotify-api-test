<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\AlbumInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\Statistics\AlbumStatistics;
use App\Entities\Statistics\TrackStatistics;
use App\Entities\UserInterface;

class AlbumStatisticsProvider implements AlbumStatisticsProviderInterface
{
    private const ALPHA_MULTIPLIER = 0.25;

    public function provideForUser(AlbumInterface $album, UserInterface $user): AlbumStatistics
    {
        $statistics = [];

        $albumStatistics = new AlbumStatistics();

        $maxPlaybacks = 0;
        $minPlaybacks = INF;

        /** @var TrackInterface $track */
        foreach ($album->getTracks() as $track) {
            $statistics[] = [
                'playbacks' => $playbacks = $track->getPlaybackCountByUser($user),
                'id' => $track->getId(),
            ];

            if ($playbacks > $maxPlaybacks) {
                $maxPlaybacks = $playbacks;
            }

            if ($playbacks < $minPlaybacks) {
                $minPlaybacks = $playbacks;
            }
        }

        if ($maxPlaybacks === $minPlaybacks) {
            $minPlaybacks = 0;
        }

        foreach ($statistics as $statistic) {
            $alphaPercentage = $maxPlaybacks === 0
                ? 0
                : ($statistic['playbacks'] - $minPlaybacks) / $maxPlaybacks;

            $albumStatistics->addTrackStatistics(
                new TrackStatistics($alphaPercentage * self::ALPHA_MULTIPLIER, $statistic['playbacks'], $statistic['id'])
            );
        }

        return $albumStatistics;
    }
}
