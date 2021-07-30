<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\TrackFactory;
use App\Http\Api\Responses\ResponseBodies\RecommendationsResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class RecommendationsResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private TrackFactory $trackFactory;

    public function __construct(
        TrackFactory $trackFactory
    ) {
        $this->trackFactory = $trackFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $responseContent = json_decode($response->getBody()->getContents(), true);

        $responseBody = new RecommendationsResponseBody();

        $responseBody->setSeeds($responseContent['seeds']);

        foreach ($responseContent['tracks'] as $track) {
            $responseBody->addTrack(
                $this->trackFactory->create($track)
            );
        }

        return $responseBody;
    }
}
