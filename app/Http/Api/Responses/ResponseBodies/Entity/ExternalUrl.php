<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

class ExternalUrl implements EntityInterface
{
    private ?string $spotify = null;

    public function getSpotify(): ?string
    {
        return $this->spotify;
    }

    public function setSpotify(?string $spotify): void
    {
        $this->spotify = $spotify;
    }

    public function toArray(): array
    {
        return [
            'spotify' => $this->spotify
        ];
    }
}
