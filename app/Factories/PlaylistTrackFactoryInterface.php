<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\PlaylistTrackInterface;
use App\Entities\Spotify\TrackInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack;

interface PlaylistTrackFactoryInterface extends EntityFactoryInterface
{
    public function createFromSpotifyEntity(
        PlaylistTrack $entity,
        TrackInterface $track,
        PlaylistInterface $playlist
    ): PlaylistTrackInterface;
}
