<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\RequestBodies;

use App\Http\Api\Requests\RequestBodies\CreatePlaylistRequestBody;

class CreatePlaylistRequestBodyFactory
{
    public function create(
        string $name,
        ?bool $public = null,
        ?bool $collaborative = null,
        ?string $description = null
    ): CreatePlaylistRequestBody {
        $requestBody = new CreatePlaylistRequestBody();

        $requestBody->setName($name);
        $requestBody->setPublic($public);
        $requestBody->setCollaborative($collaborative);
        $requestBody->setDescription($description);

        return $requestBody;
    }
}
