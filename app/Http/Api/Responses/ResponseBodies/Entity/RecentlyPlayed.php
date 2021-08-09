<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

use DateTimeInterface;

class RecentlyPlayed implements EntityInterface
{
    private ?Track $track = null;

    private ?DateTimeInterface $playedAt = null;

    private ?array $context = null;

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(?Track $track): void
    {
        $this->track = $track;
    }

    public function getPlayedAt(): ?DateTimeInterface
    {
        return $this->playedAt;
    }

    public function setPlayedAt(?DateTimeInterface $playedAt): void
    {
        $this->playedAt = $playedAt;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(?array $context): void
    {
        $this->context = $context;
    }

    public function toArray(): array
    {
        return [
            'track' => isset($this->track) ? $this->track->toArray() : null,
            'playedAt' => isset($this->playedAt) ? $this->playedAt->format('Y-m-d H:i:s') : null,
            'context' => $this->context,
        ];
    }
}
