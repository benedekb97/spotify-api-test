<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Entities\UserInterface;

interface SpotifyReauthenticationServiceInterface
{
    public function reauthenticate(UserInterface $user): void;
}
