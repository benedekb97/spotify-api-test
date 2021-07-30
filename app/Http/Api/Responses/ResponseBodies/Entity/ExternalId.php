<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

class ExternalId
{
    private ?string $ean = null;

    private ?string $isrc = null;

    private ?string $upc = null;

    public function getEan(): ?string
    {
        return $this->ean;
    }

    public function setEan(?string $ean): void
    {
        $this->ean = $ean;
    }

    public function getIsrc(): ?string
    {
        return $this->isrc;
    }

    public function setIsrc(?string $isrc): void
    {
        $this->isrc = $isrc;
    }

    public function getUpc(): ?string
    {
        return $this->upc;
    }

    public function setUpc(?string $upc): void
    {
        $this->upc = $upc;
    }

    public function toArray(): array
    {
        return [
            'ean' => $this->ean,
            'isrc' => $this->isrc,
            'upc' => $this->upc,
        ];
    }
}
