<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use DateTimeInterface;

class PlaylistTrack implements PlaylistTrackInterface
{
    private ?int $id = null;

    private ?PlaylistInterface $playlist = null;

    private ?TrackInterface $track = null;

    private ?DateTimeInterface $addedAt = null;

    private ?string $addedByUserId = null;

    private ?bool $local = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaylist(): ?PlaylistInterface
    {
        return $this->playlist;
    }

    public function setPlaylist(?PlaylistInterface $playlist): void
    {
        $this->playlist = $playlist;
    }

    public function getTrack(): ?TrackInterface
    {
        return $this->track;
    }

    public function setTrack(?TrackInterface $track): void
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

    public function getAddedByUserId(): ?string
    {
        return $this->addedByUserId;
    }

    public function setAddedByUserId(?string $addedByUserId): void
    {
        $this->addedByUserId = $addedByUserId;
    }

    public function isLocal(): ?bool
    {
        return $this->local;
    }

    public function setLocal(?bool $local): void
    {
        $this->local = $local;
    }
}
