<?php

declare(strict_types=1);

namespace App\Services\Synchronizers;

use App\Entities\UserInterface;
use Illuminate\Support\Collection;

interface UserTracksSynchronizerInterface
{
    public function synchronize(UserInterface $user, Collection $savedTracks): void;
}
