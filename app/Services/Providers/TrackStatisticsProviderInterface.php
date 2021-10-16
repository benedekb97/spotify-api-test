<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;

interface TrackStatisticsProviderInterface
{
    public function provideForUser(TrackInterface $track, UserInterface $user): array;
}
