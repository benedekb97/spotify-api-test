<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\ProfileInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\User;

interface ProfileProviderInterface
{
    public function provide(User $user): ProfileInterface;
}
