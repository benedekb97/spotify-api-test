<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\TrackAssociationInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;

interface TrackAssociationFactoryInterface extends EntityFactoryInterface
{
    public function createFromTracksUserAndPlaylist(
        TrackInterface $originalTrack,
        TrackInterface $recommendedTrack,
        UserInterface $user,
        PlaylistInterface $playlist
    ): TrackAssociationInterface;
}
