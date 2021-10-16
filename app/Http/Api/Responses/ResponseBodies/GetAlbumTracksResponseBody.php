<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use Illuminate\Database\Eloquent\Collection;

class GetAlbumTracksResponseBody implements ResponseBodyInterface
{
    private Collection $items;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Track $track): void
    {
        $this->items->add($track);
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items->map(fn (Track $t) => $t->toArray())->toArray(),
        ];
    }
}
