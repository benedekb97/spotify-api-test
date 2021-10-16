<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\TrackFactory;
use App\Http\Api\Responses\ResponseBodies\GetAlbumTracksResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class GetAlbumTracksResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private TrackFactory $trackFactory;

    public function __construct(
        TrackFactory $trackFactory
    ) {
        $this->trackFactory = $trackFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $responseBody = new GetAlbumTracksResponseBody();

        foreach ($data['items'] as $track) {
            $responseBody->addItem(
                $this->trackFactory->create($track)
            );
        }

        return $responseBody;
    }
}
