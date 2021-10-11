<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\UserInterface;
use DateTimeInterface;

interface UserTrackInterface extends ResourceInterface, TimestampableInterface
{
    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user): void;

    public function getTrack(): ?TrackInterface;

    public function setTrack(?TrackInterface $track): void;

    public function getAddedAt(): ?DateTimeInterface;

    public function setAddedAt(?DateTimeInterface $addedAt): void;

    public function getPlaybackCount(): int;
}
