<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

use DateTimeInterface;

class SavedTrack implements EntityInterface
{
    private ?DateTimeInterface $addedAt = null;

    private ?Track $track = null;

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(?Track $track): void
    {
        $this->track = $track;
    }

    public function getAddedAt(): ?DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(?DateTimeInterface $addedAt): void
    {
        $this->addedAt = $addedAt;
    }

    public function toArray(): array
    {
        return [
            'track' => $this->track->toArray(),
            'addedAt' => $this->addedAt->format('Y-m-d H:i:s')
        ];
    }
}
