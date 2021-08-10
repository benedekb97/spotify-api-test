<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\PlaylistFactory;
use App\Http\Api\Responses\ResponseBodies\GetUserPlaylistsResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class GetUserPlaylistsResponseBodyFactory implements ResponseBodyFactoryInterface
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

        $responseBody = new GetUserPlaylistsResponseBody();

        if (isset($data['items'])) {
            foreach ($data['items'] as $playlist) {
                $responseBody->addPlaylist(
                    $this->playlistFactory->create($playlist)
                );
            }
        }

        if (isset($data['limit'])) {
            $responseBody->setLimit($data['limit']);
        }

        if (isset($data['offset'])) {
            $responseBody->setOffset($data['offset']);
        }

        if (isset($data['total'])) {
            $responseBody->setTotal($data['total']);
        }

        return $responseBody;
    }
}
