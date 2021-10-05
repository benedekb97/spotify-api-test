<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\UserInterface;

interface PlaylistRecommendedTrackProviderInterface
{
    public function provide(PlaylistInterface $playlist, UserInterface $user, ?int $max = null): array;
}
