<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\UserInterface;
use DateTime;
use DateTimeInterface;

class Playback implements PlaybackInterface
{
    private ?int $id = null;

    private ?TrackInterface $track = null;

    private ?UserInterface $user = null;

    private ?DateTimeInterface $createdAt = null;

    private ?DateTimeInterface $updatedAt = null;

    private ?DateTimeInterface $playedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrack(): ?TrackInterface
    {
        return $this->track;
    }

    public function setTrack(?TrackInterface $track): void
    {
        $this->track = $track;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getPlayedAt(): ?DateTimeInterface
    {
        return $this->playedAt;
    }

    public function setPlayedAt(?DateTimeInterface $playedAt): void
    {
        $this->playedAt = $playedAt;
    }

    public function setPlayedAtNow(): void
    {
        $this->playedAt = new DateTime();
    }
}
