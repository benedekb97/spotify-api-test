<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

class Followers
{
    private ?string $href = null;

    private ?int $total = null;

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): void
    {
        $this->href = $href;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    public function toArray(): array
    {
        return [
            'href' => $this->href,
            'total' => $this->total
        ];
    }
}
