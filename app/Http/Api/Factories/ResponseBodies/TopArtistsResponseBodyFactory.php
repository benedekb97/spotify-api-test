<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\ArtistFactory;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use App\Http\Api\Responses\ResponseBodies\TopArtistsResponseBody;
use GuzzleHttp\Psr7\Response;

class TopArtistsResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private ArtistFactory $artistFactory;

    public function __construct(
        ArtistFactory $artistFactory
    ) {
        $this->artistFactory = $artistFactory;
    }

    public function create(Response $response): ResponseBodyInterface
    {
        $responseContent = json_decode($response->getBody()->getContents(), true);

        $object = new TopArtistsResponseBody();

        $object->setHref($responseContent['href']);
        $object->setLimit($responseContent['limit']);
        $object->setNext($responseContent['next']);
        $object->setPrevious($responseContent['previous']);
        $object->setOffset($responseContent['offset']);
        $object->setTotal($responseContent['total']);

        foreach ($responseContent['items'] as $artist) {
            $object->addItem(
                $this->artistFactory->create($artist)
            );
        }

        return $object;
    }
}
