<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\AlbumInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Album;

interface AlbumProviderInterface
{
    public function provide(Album $entity): AlbumInterface;
}
