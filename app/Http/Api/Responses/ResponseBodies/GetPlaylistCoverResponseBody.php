<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use Illuminate\Database\Eloquent\Collection;

class GetPlaylistCoverResponseBody implements ResponseBodyInterface
{
    private Collection $images;

    public function __construct()
    {
        $this->images = new Collection();
    }

    public function addImage(Image $image): void
    {
        $this->images->add($image);
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function toArray(): array
    {
        return $this->images->map(fn (Image $i) => $i->toArray())->toArray();
    }
}
