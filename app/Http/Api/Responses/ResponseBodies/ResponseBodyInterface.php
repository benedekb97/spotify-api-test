<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

interface ResponseBodyInterface
{
    public function toArray(): array;
}
