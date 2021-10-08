<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\TrackInterface;
use App\Entities\Spotify\UserTrackInterface;
use App\Entities\UserInterface;

class UserTrackFactory extends EntityFactory implements UserTrackFactoryInterface
{
    public function createForUserAndTrack(
        UserInterface $user,
        TrackInterface $track
    ): UserTrackInterface
    {
        /** @var UserTrackInterface $userTrack */
        $userTrack = $this->createNew();

        $user->addUserTrack($userTrack);

        $userTrack->setTrack($track);

        return $userTrack;
    }
}
