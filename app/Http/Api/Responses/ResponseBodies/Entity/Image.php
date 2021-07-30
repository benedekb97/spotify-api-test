<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

class Image
{
    private ?int $height = null;

    private ?int $width = null;

    private ?string $url = null;

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): void
    {
        $this->width = $width;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function toArray(): array
    {
        return [
            'height' => $this->height,
            'width' => $this->width,
            'url' => $this->url,
        ];
    }
}
