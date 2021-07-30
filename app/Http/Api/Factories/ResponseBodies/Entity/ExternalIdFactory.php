<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\ExternalId;

class ExternalIdFactory
{
    public function create(array $data): ExternalId
    {
        $externalId = new ExternalId();

        $externalId->setEan($data['ean'] ?? null);
        $externalId->setIsrc($data['isrc'] ?? null);
        $externalId->setUpc($data['upc'] ?? null);

        return $externalId;
    }
}
