<?php

declare(strict_types=1);

namespace App\Entities\Statistics;

class TrackStatistics
{
    private float $alpha;

    private int $playbacks;

    private string $id;

    public function __construct(
        float $alpha,
        int $playbacks,
        string $id
    ) {
        $this->alpha = $alpha;
        $this->playbacks = $playbacks;
        $this->id = $id;
    }

    public function getAlpha(): float
    {
        return $this->alpha;
    }

    public function getPlaybacks(): int
    {
        return $this->playbacks;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
