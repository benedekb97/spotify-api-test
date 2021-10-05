<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\UserInterface;

interface TrackAssociationInterface
{
    public function getId(): ?int;

    public function getOriginalTrack(): ?TrackInterface;

    public function setOriginalTrack(?TrackInterface $track): void;

    public function getRecommendedTrack(): ?TrackInterface;

    public function setRecommendedTrack(?TrackInterface $track): void;

    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user): void;

    public function getPlaylist(): ?PlaylistInterface;

    public function setPlaylist(?PlaylistInterface $playlist): void;
}
