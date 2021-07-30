<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use Illuminate\Database\Eloquent\Collection;

class TopTracksResponseBody implements ResponseBodyInterface
{
    /** @var Collection|Track[] */
    private Collection $items;

    private ?int $total = null;

    private ?int $limit = null;

    private ?int $offset = null;

    private ?string $previous = null;

    private ?string $href = null;

    private ?string $next = null;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function getItems(): ?Collection
    {
        return $this->items;
    }

    public function addItem(Track $artist): void
    {
        $this->items->add($artist);
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
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

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): void
    {
        $this->href = $href;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function setNext(?string $next): void
    {
        $this->next = $next;
    }
}
