<?php

declare(strict_types=1);

namespace App\Entities\Statistics;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class AlbumStatistics
{
    private Collection $trackStatistics;

    public function __construct()
    {
        $this->trackStatistics = new ArrayCollection();
    }

    public function addTrackStatistics(TrackStatistics $trackStatistics)
    {
        $this->trackStatistics->add($trackStatistics);
    }

    public function getTrackStatistics(): Collection
    {
        return $this->trackStatistics;
    }

    public function getTrackStatisticsById(string $id): ?TrackStatistics
    {
        return $this->trackStatistics->filter(
            static function (TrackStatistics $trackStatistics) use ($id): bool
            {
                return $trackStatistics->getId() === $id;
            }
        )->first();
    }
}
