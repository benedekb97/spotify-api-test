<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;

interface ScopeInterface extends ResourceInterface, TimestampableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;
}
