<?php

declare(strict_types=1);

namespace App\Entities;

use DateTime;
use DateTimeInterface;

trait TimestampableTrait
{
    private ?DateTimeInterface $createdAt = null;

    private ?DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setCreatedAtNow(): void
    {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setUpdatedAtNow(): void
    {
        $this->updatedAt = new DateTime();
    }
}
