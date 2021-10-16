<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use App\Entities\UserInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use Illuminate\Foundation\Events\Dispatchable;

class UpdateCurrentlyPlayingEvent
{
    use Dispatchable;

    private UserInterface $user;

    private ?Track $track;

    public function __construct(UserInterface $user, ?Track $track)
    {
        $this->user = $user;
        $this->track = $track;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getTrack(): ?Track
    {
        return $this->track;
    }
}
