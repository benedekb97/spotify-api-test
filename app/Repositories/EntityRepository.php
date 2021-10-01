<?php

declare(strict_types=1);

namespace App\Repositories;

use Doctrine\ORM\EntityRepository as BaseEntityRepository;

class EntityRepository extends BaseEntityRepository
{
    public function add(object $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }
}
