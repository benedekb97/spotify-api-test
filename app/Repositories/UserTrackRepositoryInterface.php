<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Spotify\TrackInterface;
use App\Entities\Spotify\UserTrackInterface;
use App\Entities\UserInterface;
use DateTimeInterface;
use Doctrine\Persistence\ObjectRepository;

interface UserTrackRepositoryInterface extends ObjectRepository
{
    public function findOneByTrackUserAndAddedAt(
        TrackInterface $track,
        UserInterface $user,
        DateTimeInterface $addedAt
    ): ?UserTrackInterface;
}
