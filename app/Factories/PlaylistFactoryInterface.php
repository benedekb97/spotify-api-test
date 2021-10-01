<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\PlaylistInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Playlist;

interface PlaylistFactoryInterface extends EntityFactoryInterface
{
    public function createFromSpotifyEntity(Playlist $entity): PlaylistInterface;
}
