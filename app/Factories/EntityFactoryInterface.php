<?php

declare(strict_types=1);

namespace App\Factories;

interface EntityFactoryInterface
{
    public function createNew(): object;
}
