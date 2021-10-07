<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\ArtistInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist;

interface ArtistProviderInterface
{
    public function provide(Artist $entity): ArtistInterface;
}
