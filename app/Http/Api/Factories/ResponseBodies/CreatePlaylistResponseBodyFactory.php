<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\PlaylistFactory;
use App\Http\Api\Responses\ResponseBodies\CreatePlaylistResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class CreatePlaylistResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private PlaylistFactory $playlistFactory;

    public function __construct(
        PlaylistFactory $playlistFactory
    ) {
        $this->playlistFactory = $playlistFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $body = new CreatePlaylistResponseBody();

        $body->setPlaylist(
            $this->playlistFactory->create($data)
        );

        return $body;
    }
}
