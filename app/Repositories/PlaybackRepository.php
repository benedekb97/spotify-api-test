<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;
use DateTimeInterface;
use Doctrine\ORM\QueryBuilder;

class PlaybackRepository extends EntityRepository implements PlaybackRepositoryInterface
{
    public function getRecentPlaybacksByUser(UserInterface $user): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->orderBy('o.playedAt', 'DESC')
            ->setParameter('user', $user)
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    /** @inheritDoc */
    public function getPlaybacksForUserBetween(
        UserInterface $user,
        DateTimeInterface $start,
        DateTimeInterface $end
    ): array
    {
        return $this->createQueryBuilderBetween($start, $end)
            ->andWhere('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /** @inheritDoc */
    public function getPlaybacksForUserAndTrackBetween(
        UserInterface $user,
        TrackInterface $track,
        DateTimeInterface $start,
        DateTimeInterface $end
    ): array
    {
        return $this->createQueryBuilderBetween($start, $end)
            ->andWhere('o.user = :user')
            ->andWhere('o.track = :track')
            ->setParameter('user', $user)
            ->setParameter('track', $track)
            ->getQuery()
            ->getResult();
    }

    private function createQueryBuilderBetween(
        DateTimeInterface $start,
        DateTimeInterface $end,
        string $alias = 'o'
    ): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->where($alias . '.playedAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);
    }
}
