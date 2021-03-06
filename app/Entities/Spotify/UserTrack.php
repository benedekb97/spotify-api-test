<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\UserInterface;
use DateTimeInterface;

class UserTrack implements UserTrackInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    private ?UserInterface $user = null;

    private ?TrackInterface $track = null;

    private ?DateTimeInterface $addedAt = null;

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
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

    public function getPlaybackCount(): int
    {
        return $this->user->getPlaybacks()->filter(
            function (PlaybackInterface $playback): bool
            {
                return $playback->getTrack() === $this->track;
            }
        )->count();
    }
}
