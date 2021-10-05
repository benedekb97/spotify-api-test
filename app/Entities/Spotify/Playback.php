<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\UserInterface;
use DateTime;
use DateTimeInterface;

class Playback implements PlaybackInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    private ?TrackInterface $track = null;

    private ?UserInterface $user = null;

    private ?DateTimeInterface $playedAt = null;

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
