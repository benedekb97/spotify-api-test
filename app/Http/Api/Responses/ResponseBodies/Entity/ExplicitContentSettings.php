<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

class ExplicitContentSettings implements EntityInterface
{
    private ?bool $filterEnabled = null;

    private ?bool $filterLocked = null;

    public function getFilterEnabled(): ?bool
    {
        return $this->filterEnabled;
    }

    public function setFilterEnabled(?bool $filterEnabled): void
    {
        $this->filterEnabled = $filterEnabled;
    }

    public function getFilterLocked(): ?bool
    {
        return $this->filterLocked;
    }

    public function setFilterLocked(?bool $filterLocked): void
    {
        $this->filterLocked = $filterLocked;
    }

    public function toArray(): array
    {
        return [
            'filterEnabled' => $this->filterEnabled,
            'filterLocked' => $this->filterLocked,
        ];
    }
}
