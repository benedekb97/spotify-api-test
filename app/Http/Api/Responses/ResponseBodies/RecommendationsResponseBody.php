<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use Illuminate\Database\Eloquent\Collection;

class RecommendationsResponseBody implements ResponseBodyInterface
{
    /** @var Collection|Track[] */
    private Collection $tracks;

    private ?array $seeds;

    public function __construct()
    {
        $this->tracks = new Collection();
    }

    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): void
    {
        $this->tracks->add($track);
    }

    public function getSeeds(): ?array
    {
        return $this->seeds;
    }

    public function setSeeds(?array $seeds): void
    {
        $this->seeds = $seeds;
    }

    public function toArray(): array
    {
        return [
            'tracks' => $this->tracks->map(static fn (Track $t) => $t->toArray())->toArray(),
            'seeds' => $this->seeds,
        ];
    }
}
