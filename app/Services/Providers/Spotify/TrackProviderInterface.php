<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\TrackInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Track;

interface TrackProviderInterface
{
    public function provideForId(string $id): TrackInterface;

    public function provide(Track $entity): TrackInterface;
}
