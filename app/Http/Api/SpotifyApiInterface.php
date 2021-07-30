<?php

declare(strict_types=1);

namespace App\Http\Api;

use App\Http\Api\Requests\SpotifyRequestInterface;
use App\Http\Api\Responses\SpotifyResponseInterface;

interface SpotifyApiInterface
{
    public function execute(
        SpotifyRequestInterface $request,
        ...$requestBodyFactoryParameters
    ): ?SpotifyResponseInterface;
}
