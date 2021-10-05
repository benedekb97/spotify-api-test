<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\UserInterface;

class PlaylistRepository extends EntityRepository implements PlaylistRepositoryInterface
{
    public function getTopPlayedPlaylistsForUser(UserInterface $user): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.localUser = :user')
            ->andWhere('o.topPlayed = true')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
