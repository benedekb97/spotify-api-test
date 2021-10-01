<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ScopeInterface;
use Doctrine\Persistence\ObjectRepository;

interface ScopeRepositoryInterface extends ObjectRepository
{
    public function findOneByName(string $name): ?ScopeInterface;
}
