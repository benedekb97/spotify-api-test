<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

class Device implements EntityInterface
{
    private ?string $id = null;

    private ?bool $isActive = null;

    private ?bool $isPrivateSession = null;

    private ?bool $isRestricted = null;

    private ?string $name = null;

    private ?string $type = null;

    private ?int $volumePercentage = null;

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function setIsPrivateSession(?bool $isPrivateSession): void
    {
        $this->isPrivateSession = $isPrivateSession;
    }

    public function setIsRestricted(?bool $isRestricted): void
    {
        $this->isRestricted = $isRestricted;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function setVolumePercentage(?int $volumePercentage): void
    {
        $this->volumePercentage = $volumePercentage;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function getIsPrivateSession(): ?bool
    {
        return $this->isPrivateSession;
    }

    public function getIsRestricted(): ?bool
    {
        return $this->isRestricted;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getVolumePercentage(): ?int
    {
        return $this->volumePercentage;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'isActive' => $this->isActive,
            'isPrivateSession' => $this->isPrivateSession,
            'isRestricted' => $this->isRestricted,
            'name' => $this->name,
            'type' => $this->type,
            'volumePercentage' => $this->volumePercentage,
        ];
    }
}
