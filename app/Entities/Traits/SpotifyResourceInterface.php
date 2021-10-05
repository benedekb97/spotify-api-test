<?php

declare(strict_types=1);

namespace App\Entities\Traits;

interface SpotifyResourceInterface
{
    public function getId(): ?string;

    public function setId(?string $id): void;
}
