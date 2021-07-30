<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\ExternalUrl;

class ExternalUrlFactory
{
    public function create(array $data): ExternalUrl
    {
        $externalUrl = new ExternalUrl();

        $externalUrl->setSpotify($data['spotify']);

        return $externalUrl;
    }
}
