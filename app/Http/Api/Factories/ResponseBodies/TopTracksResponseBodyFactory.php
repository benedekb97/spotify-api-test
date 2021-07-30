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

        $object->setHref($data['href']);
        $object->setLimit($data['limit']);
        $object->setNext($data['next']);
        $object->setPrevious($data['previous']);
        $object->setOffset($data['offset']);
        $object->setTotal($data['total']);

        foreach ($data['items'] as $artist) {
            $object->addItem(
                $this->trackFactory->create($artist)
            );
        }

        return $object;
    }
}
