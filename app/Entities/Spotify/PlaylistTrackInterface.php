<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\ResourceInterface;
use DateTimeInterface;

interface PlaylistTrackInterface extends ResourceInterface
{
    public function getPlaylist(): ?PlaylistInterface;

    public function setPlaylist(?PlaylistInterface $playlist): void;

    public function getTrack(): ?TrackInterface;

    public function setTrack(?TrackInterface $track): void;

    public function getAddedAt(): ?DateTimeInterface;

    public function setAddedAt(?DateTimeInterface $addedAt): void;

    public function getAddedByUserId(): ?string;

    public function setAddedByUserId(?string $addedByUserId): void;

    public function isLocal(): ?bool;

    public function setLocal(?bool $local): void;
}
