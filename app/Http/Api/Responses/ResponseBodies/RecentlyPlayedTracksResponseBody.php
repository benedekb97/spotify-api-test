<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\RecentlyPlayed;
use Illuminate\Database\Eloquent\Collection;

class RecentlyPlayedTracksResponseBody implements ResponseBodyInterface
{
    /** @var Collection|RecentlyPlayed[] */
    private Collection $items;

    private ?string $next = null;

    private ?array $cursors = null;

    private ?int $limit = null;

    private ?string $href = null;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(RecentlyPlayed $item): void
    {
        $this->items->add($item);
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function setNext(?string $next): void
    {
        $this->next = $next;
    }

    public function getCursors(): ?array
    {
        return $this->cursors;
    }

    public function setCursors(?array $cursors): void
    {
        $this->cursors = $cursors;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): void
    {
        $this->href = $href;
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items->map(static fn (RecentlyPlayed $rp) => $rp->toArray())->toArray(),
            'next' => $this->next,
            'cursors' => $this->cursors,
            'limit' => $this->limit,
            'href' => $this->href,
        ];
    }
}
