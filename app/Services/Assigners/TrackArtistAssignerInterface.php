<?php

declare(strict_types=1);

namespace App\Services\Assigners;

use App\Entities\Spotify\TrackInterface;
use Doctrine\Common\Collections\Collection;

interface TrackArtistAssignerInterface
{
    public function assign(TrackInterface $track, Collection $artists): void;
}
