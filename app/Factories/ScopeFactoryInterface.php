<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\ScopeInterface;

interface ScopeFactoryInterface extends EntityFactoryInterface
{
    public function create(string $scopeName): ScopeInterface;
}