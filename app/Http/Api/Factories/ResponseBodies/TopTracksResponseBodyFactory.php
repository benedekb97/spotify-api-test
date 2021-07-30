<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\TrackFactory;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use App\Http\Api\Responses\ResponseBodies\TopTracksResponseBody;
use GuzzleHttp\Psr7\Response;

class TopTracksResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private TrackFactory $trackFactory;

    public function __construct(
        TrackFactory $trackFactory
    ) {
        $this->trackFactory = $trackFactory;
    }

    public function create(Response $response): ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $object = new TopTracksResponseBody();

        $object->setHref($data['href'] ?? null);
        $object->setLimit($data['limit'] ?? null);
        $object->setNext($data['next'] ?? null);
        $object->setPrevious($data['previous'] ?? null);
        $object->setOffset($data['offset'] ?? null);
        $object->setTotal($data['total'] ?? null);

        foreach ($data['items'] as $artist) {
            $object->addItem(
                $this->trackFactory->create($artist)
            );
        }

        return $object;
    }
}
