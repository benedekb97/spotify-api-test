<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\SavedTrackFactory;
use App\Http\Api\Responses\ResponseBodies\GetUserTracksResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class GetUserTracksResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private SavedTrackFactory $savedTrackFactory;

    public function __construct(
        SavedTrackFactory $savedTrackFactory
    ) {
        $this->savedTrackFactory = $savedTrackFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $responseBody = new GetUserTracksResponseBody();

        foreach ($data['items'] as $item) {
            $responseBody->addSavedTrack(
                $this->savedTrackFactory->create($item)
            );
        }

        $responseBody->setLimit($data['limit']);
        $responseBody->setOffset($data['offset']);

        return $responseBody;
    }
}
