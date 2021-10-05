<?php

declare(strict_types=1);

namespace App\Entities\Traits;

trait SpotifyResourceTrait
{
    private ?string $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }
}
