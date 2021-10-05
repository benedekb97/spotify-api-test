<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;

class Scope implements ScopeInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
