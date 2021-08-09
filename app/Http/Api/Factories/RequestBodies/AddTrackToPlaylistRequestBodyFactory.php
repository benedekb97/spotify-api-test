<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\RequestBodies;

use App\Http\Api\Requests\RequestBodies\AddTrackToPlaylistRequestBody;

class AddTrackToPlaylistRequestBodyFactory
{
    public function create(
        ?array $uris,
        ?int $position = null
    ): AddTrackToPlaylistRequestBody {
        $requestBody = new AddTrackToPlaylistRequestBody();

        $requestBody->setUris($uris);
        $requestBody->setPosition($position);

        return $requestBody;
    }
}
