<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\SavedTrack;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class GetUserTracksResponseBody implements ResponseBodyInterface
{
    private Collection $savedTracks;

    private ?int $limit = null;

    private ?int $offset = null;

    private ?int $total = null;

    public function __construct()
    {
        $this->savedTracks = new ArrayCollection();
    }

    public function getSavedTracks(): Collection
    {
        return $this->savedTracks;
    }

    public function hasSavedTrack(SavedTrack $savedTrack): bool
    {
        return $this->savedTracks->contains($savedTrack);
    }

    public function addSavedTrack(SavedTrack $savedTrack): void
    {
        if (!$this->hasSavedTrack($savedTrack)) {
            $this->savedTracks->add($savedTrack);
        }
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
            'savedTracks' => $this->savedTracks->map(fn (SavedTrack $st) => $st->toArray())->toArray(),
        ];
    }
}
