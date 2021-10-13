<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\UserInterface;
use DateTime;
use Doctrine\Common\Collections\Collection;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    public function findOneByEmail(string $email): ?UserInterface
    {
        return $this->createQueryBuilder('o')
            ->where('o.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNulLResult();
    }

    public function findAllLoggedInWithSpotify(): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.spotifyAccessToken IS NOT NULL')
            ->andWhere('o.spotifyRefreshToken IS NOT NULL')
            ->andWhere('o.spotifyAccessTokenExpiry IS NOT NULL')
            ->andWhere('o.spotifyAccessTokenExpiry > :currentDateTime')
            ->setParameter('currentDateTime', new DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findOneBySpotifyId(string $id): ?UserInterface
    {
        return $this->createQueryBuilder('o')
            ->where('o.spotifyId = :spotifyId')
            ->setParameter('spotifyId', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
