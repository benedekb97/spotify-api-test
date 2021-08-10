<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\Playlist;
use Illuminate\Database\Eloquent\Collection;

class GetUserPlaylistsResponseBody implements ResponseBodyInterface
{
    private Collection $playlists;

    private ?int $total = null;

    private ?int $offset = null;

    private ?int $limit = null;

    public function __construct()
    {
        $this->playlists = new Collection();
    }

    public function addPlaylist(Playlist $playlist): void
    {
        $this->playlists->add($playlist);
    }

    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(?int $offset): void
    {
        $this->offset = $offset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    public function toArray(): array
    {
        return [
            'playlists' => $this->playlists->map(fn (Playlist $p) => $p->toArray())->toArray(),
            'limit' => $this->limit,
            'offset' => $this->offset,
            'total' => $this->total,
        ];
    }
}
