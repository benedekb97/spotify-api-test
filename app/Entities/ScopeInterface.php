<?php

declare(strict_types=1);

namespace App\Entities;

use DateTimeInterface;

interface ScopeInterface
{
    public function getId(): ?int;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(?DateTimeInterface $createdAt): void;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void;
}
