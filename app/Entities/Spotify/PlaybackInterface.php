<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\UserInterface;
use DateTimeInterface;

interface PlaybackInterface
{
    public function getId(): ?int;

    public function getTrack(): ?TrackInterface;

    public function setTrack(?TrackInterface $track): void;

    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user): void;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(?DateTimeInterface $createdAt): void;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void;

    public function getPlayedAt(): ?DateTimeInterface;

    public function setPlayedAt(?DateTimeInterface $playedAt): void;

    public function setPlayedAtNow(): void;
}
