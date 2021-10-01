<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectRepository;

interface UserRepositoryInterface extends ObjectRepository
{
    public function findOneByEmail(string $email): ?UserInterface;

    public function findAllLoggedInWithSpotify(): array;
}
