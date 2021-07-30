<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

interface ResponseBodyFactoryInterface
{
    public function create(Response $response): ?ResponseBodyInterface;
}
