<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\PlaylistTrackFactory;
use App\Http\Api\Responses\ResponseBodies\GetPlaylistItemsResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class GetPlaylistItemsResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private PlaylistTrackFactory $playlistTrackFactory;

    public function __construct(
        PlaylistTrackFactory $playlistTrackFactory
    )
    {
        $this->playlistTrackFactory = $playlistTrackFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $responseBody = new GetPlaylistItemsResponseBody();

        if (isset($data['items'])) {
            foreach ($data['items'] as $playlistTrack) {
                $responseBody->addItem(
                    $this->playlistTrackFactory->create($playlistTrack)
                );
            }
        }

        $responseBody->setHref($data['href']);
        $responseBody->setOffset($data['offset']);
        $responseBody->setLimit($data['limit']);
        $responseBody->setNext($data['next']);
        $responseBody->setPrevious($data['previous']);
        $responseBody->setTotal($data['total']);

        return $responseBody;
    }
}
