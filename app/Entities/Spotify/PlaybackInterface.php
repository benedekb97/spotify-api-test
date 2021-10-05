<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\UserInterface;
use DateTimeInterface;

interface PlaybackInterface extends ResourceInterface, TimestampableInterface
{
    public function getTrack(): ?TrackInterface;

    public function setTrack(?TrackInterface $track): void;

    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user): void;

    public function getPlayedAt(): ?DateTimeInterface;

    public function setPlayedAt(?DateTimeInterface $playedAt): void;

    public function setPlayedAtNow(): void;
}
