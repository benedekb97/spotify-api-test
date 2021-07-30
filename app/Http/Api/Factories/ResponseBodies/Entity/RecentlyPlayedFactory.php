<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\RecentlyPlayed;
use DateTime;

class RecentlyPlayedFactory
{
    private TrackFactory $trackFactory;

    public function __construct(
        TrackFactory $trackFactory
    ) {
        $this->trackFactory = $trackFactory;
    }

    public function create(array $data): RecentlyPlayed
    {
        $recentlyPlayed = new RecentlyPlayed();

        $recentlyPlayed->setTrack(
            $this->trackFactory->create($data['track'])
        );

        $recentlyPlayed->setPlayedAt(
            new DateTime($data['played_at'])
        );

        $recentlyPlayed->setContext($data['context']);

        return $recentlyPlayed;
    }
}
