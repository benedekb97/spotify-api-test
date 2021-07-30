<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\Track;

class CurrentlyPlayingResponseBody implements ResponseBodyInterface
{
    private ?int $timestamp = null;

    private ?string $context = null;

    private ?int $progressMs = null;

    private ?Track $item;

    private ?string $currentlyPlayingType = null;

    private ?array $actions = null;

    private ?bool $isPlaying = null;

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(?int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): void
    {
        $this->context = $context;
    }

    public function getProgressMs(): ?int
    {
        return $this->progressMs;
    }

    public function setProgressMs(?int $progressMs): void
    {
        $this->progressMs = $progressMs;
    }

    public function getItem(): ?Track
    {
        return $this->item;
    }

    public function setItem(?Track $item): void
    {
        $this->item = $item;
    }

    public function getCurrentlyPlayingType(): ?string
    {
        return $this->currentlyPlayingType;
    }

    public function setCurrentlyPlayingType(?string $currentlyPlayingType): void
    {
        $this->currentlyPlayingType = $currentlyPlayingType;
    }

    public function getActions(): ?array
    {
        return $this->actions;
    }

    public function setActions(?array $actions): void
    {
        $this->actions = $actions;
    }

    public function getIsPlaying(): ?bool
    {
        return $this->isPlaying;
    }

    public function setIsPlaying(?bool $isPlaying): void
    {
        $this->isPlaying = $isPlaying;
    }

    public function toArray(): array
    {
        return [
            'timestamp' => $this->timestamp,
            'context' => $this->context,
            'progressMs' => $this->progressMs,
            'item' => isset($this->item) ? $this->item->toArray() : null,
            'currentlyPlayingType' => $this->currentlyPlayingType,
            'actions' => $this->actions,
            'isPlaying' => $this->isPlaying
        ];
    }
}
