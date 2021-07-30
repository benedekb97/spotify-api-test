<?php

declare(strict_types=1);

namespace App\Http\Api\Requests\RequestBodies;

interface RequestBodyInterface
{
    public function toArray(): array;
}
