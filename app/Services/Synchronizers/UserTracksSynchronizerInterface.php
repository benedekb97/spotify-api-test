<?php

declare(strict_types=1);

namespace App\Services\Synchronizers;

use App\Entities\UserInterface;

interface UserTracksSynchronizerInterface
{
    public function synchronize(UserInterface $user, array $savedTracks): void;
}
