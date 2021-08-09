<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

use DateTimeInterface;

class PlaylistTrack implements EntityInterface
{
    private ?DateTimeInterface $addedAt = null;

    private ?PublicUser $addedBy = null;

    private ?bool $isLocal = null;

    private ?Track $track = null;

    public function getAddedAt(): ?DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(?DateTimeInterface $addedAt): void
    {
        $this->addedAt = $addedAt;
    }

    public function getAddedBy(): ?PublicUser
    {
        return $this->addedBy;
    }

    public function setAddedBy(?PublicUser $addedBy): void
    {
        $this->addedBy = $addedBy;
    }

    public function isLocal(): ?bool
    {
        return $this->isLocal;
    }

    public function setIsLocal(?bool $isLocal): void
    {
        $this->isLocal = $isLocal;
    }

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(?Track $track): void
    {
        $this->track = $track;
    }

    public function toArray(): array
    {
        return [
            'addedAt' => isset($this->addedAt) ? $this->addedAt->format('Y-m-d H:i:s') : null,
            'addedBy' => isset($this->addedBy) ? $this->addedBy->toArray() : null,
            'isLocal' => $this->isLocal,
            'track' => isset($this->track) ? $this->track->toArray() : null,
        ];
    }
}
