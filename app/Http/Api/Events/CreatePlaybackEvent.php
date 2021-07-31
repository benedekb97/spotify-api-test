<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use Illuminate\Foundation\Events\Dispatchable;

class CreatePlaybackEvent
{
    use Dispatchable;

    private ?Track $track;

    public function __construct(?Track $track)
    {
        if ($track === null) {
            return;
        }

        $this->track = $track;
    }

    public function getTrack(): ?Track
    {
        return $this->track;
    }
}
