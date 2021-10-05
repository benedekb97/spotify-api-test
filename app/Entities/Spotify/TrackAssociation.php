<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\UserInterface;

class TrackAssociation implements TrackAssociationInterface
{
    use TimestampableTrait;
    use ResourceTrait;

    private ?TrackInterface $originalTrack = null;

    private ?TrackInterface $recommendedTrack = null;

    private ?UserInterface $user = null;

    private ?PlaylistInterface $playlist = null;

    public function getOriginalTrack(): ?TrackInterface
    {
        return $this->originalTrack;
    }

    public function setOriginalTrack(?TrackInterface $track): void
    {
        $this->originalTrack = $track;
    }

    public function getRecommendedTrack(): ?TrackInterface
    {
        return $this->recommendedTrack;
    }

    public function setRecommendedTrack(?TrackInterface $track): void
    {
        $this->recommendedTrack = $track;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getPlaylist(): ?PlaylistInterface
    {
        return $this->playlist;
    }

    public function setPlaylist(?PlaylistInterface $playlist): void
    {
        $this->playlist = $playlist;
    }
}
