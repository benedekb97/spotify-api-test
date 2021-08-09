<?php

declare(strict_types=1);

namespace App\Http\Api\Requests\RequestBodies;

class AddTrackToPlaylistRequestBody implements RequestBodyInterface
{
    private ?array $uris = null;

    private ?int $position = null;

    public function getUris(): ?array
    {
        return $this->uris;
    }

    public function setUris(?array $uris): void
    {
        $this->uris = $uris;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function toArray(): array
    {
        return [
            'uris' => array_values($this->uris),
            'position' => $this->position,
        ];
    }
}
