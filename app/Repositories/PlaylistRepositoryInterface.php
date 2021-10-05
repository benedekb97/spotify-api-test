<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\UserInterface;
use Doctrine\Persistence\ObjectRepository;

interface PlaylistRepositoryInterface extends ObjectRepository
{
    public function getTopPlayedPlaylistsForUser(UserInterface $user): array;
}
