<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Spotify\TrackInterface;
use App\Entities\Spotify\UserTrackInterface;
use App\Entities\UserInterface;
use DateTimeInterface;

class UserTrackRepository extends EntityRepository implements UserTrackRepositoryInterface
{
    public function findOneByTrackUserAndAddedAt(
        TrackInterface $track,
        UserInterface $user,
        DateTimeInterface $addedAt
    ): ?UserTrackInterface
    {
        return $this->createQueryBuilder('o')
            ->where('o.track = :track')
            ->andWhere('o.user = :user')
            ->andWhere('o.addedAt = :addedAt')
            ->setParameter('track', $track)
            ->setParameter('user', $user)
            ->setParameter('addedAt', $addedAt)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
