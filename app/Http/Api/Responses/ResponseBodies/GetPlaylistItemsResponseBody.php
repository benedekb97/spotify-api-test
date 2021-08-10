<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack;
use Illuminate\Database\Eloquent\Collection;

class GetPlaylistItemsResponseBody implements ResponseBodyInterface
{
    private Collection $items;

    private ?string $href = null;

    private ?int $limit = null;

    private ?string $next = null;

    private ?int $offset = null;

    private ?string $previous = null;

    private ?int $total = null;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(PlaylistTrack $playlistTrack): void
    {
        $this->items->add($playlistTrack);
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): void
    {
        $this->href = $href;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function setNext(?string $next): void
    {
        $this->next = $next;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(?int $offset): void
    {
        $this->offset = $offset;
    }

    public function getPrevious(): ?string
    {
        return $this->previous;
    }

    public function setPrevious(?string $previous): void
    {
        $this->previous = $previous;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items->map(fn ($i) => $i->toArray())->toArray(),
        ];
    }
}
