<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;

interface RecommendedTrackProviderInterface
{
    public function provideForUserWithTrack(
        TrackInterface $track,
        UserInterface $user,
        ?int $max = null
    ): array;
}
