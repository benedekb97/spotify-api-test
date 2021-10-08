<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\TrackInterface;
use App\Entities\Spotify\UserTrackInterface;
use App\Entities\UserInterface;

interface UserTrackFactoryInterface extends EntityFactoryInterface
{
    public function createForUserAndTrack(
        UserInterface $user,
        TrackInterface $track
    ): UserTrackInterface;
}
