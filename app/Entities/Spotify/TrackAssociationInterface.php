<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\UserInterface;

interface TrackAssociationInterface extends ResourceInterface, TimestampableInterface
{
    public function getOriginalTrack(): ?TrackInterface;

    public function setOriginalTrack(?TrackInterface $track): void;

    public function getRecommendedTrack(): ?TrackInterface;

    public function setRecommendedTrack(?TrackInterface $track): void;

    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user): void;

    public function getPlaylist(): ?PlaylistInterface;

    public function setPlaylist(?PlaylistInterface $playlist): void;
}
