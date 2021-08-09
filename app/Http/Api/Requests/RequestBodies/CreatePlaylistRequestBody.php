<?php

declare(strict_types=1);

namespace App\Http\Api\Requests\RequestBodies;

class CreatePlaylistRequestBody implements RequestBodyInterface
{
    private string $name;

    private ?bool $public = null;

    private ?bool $collaborative = null;

    private ?string $description = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): void
    {
        $this->public = $public;
    }

    public function getCollaborative(): ?bool
    {
        return $this->collaborative;
    }

    public function setCollaborative(?bool $collaborative): void
    {
        $this->collaborative = $collaborative;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'public' => $this->public,
            'collaborative' => $this->collaborative,
            'description' => $this->description,
        ];
    }
}
