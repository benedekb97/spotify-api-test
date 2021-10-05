<?php

declare(strict_types=1);

namespace App\Services\Generators;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\UserInterface;

interface RecommendedPlaylistGeneratorInterface
{
    public const DEFAULT_TRACK_COUNT = 50;

    public function generate(
        PlaylistInterface $playlist,
        int $trackCount = self::DEFAULT_TRACK_COUNT
    ): PlaylistInterface;

    public function createPlaylist(UserInterface $user, PlaylistInterface $playlist): PlaylistInterface;

    public function addTracks(PlaylistInterface $playlist, UserInterface $user, array $trackUris): void;
}
