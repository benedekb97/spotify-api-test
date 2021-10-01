<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ScopeInterface;

class ScopeRepository extends EntityRepository implements ScopeRepositoryInterface
{
    public function findOneByName(string $name): ?ScopeInterface
    {
        return $this->createQueryBuilder('o')
            ->where('o.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
