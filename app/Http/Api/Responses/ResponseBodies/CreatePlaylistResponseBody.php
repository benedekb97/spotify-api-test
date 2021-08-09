<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\Playlist;

class CreatePlaylistResponseBody implements ResponseBodyInterface
{
    private ?Playlist $playlist = null;

    public function setPlaylist(?Playlist $playlist): void
    {
        $this->playlist = $playlist;
    }

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function toArray(): array
    {

    }
}
