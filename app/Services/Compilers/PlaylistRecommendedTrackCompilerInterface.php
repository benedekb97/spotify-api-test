<?php

declare(strict_types=1);

namespace App\Services\Compilers;

use App\Entities\Spotify\PlaylistInterface;

interface PlaylistRecommendedTrackCompilerInterface
{
    public function compile(PlaylistInterface $playlist): array;
}
