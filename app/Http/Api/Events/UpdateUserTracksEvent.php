<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use Doctrine\Common\Collections\Collection;
use Illuminate\Foundation\Events\Dispatchable;

class UpdateUserTracksEvent
{
    use Dispatchable;

    private Collection $tracks;

    public function __construct(
        Collection $tracks
    ) {
        $this->tracks = $tracks;
    }

    public function getTracks(): Collection
    {
        return $this->tracks;
    }
}
