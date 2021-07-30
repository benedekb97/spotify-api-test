<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\TrackFactory;
use App\Http\Api\Responses\ResponseBodies\CurrentlyPlayingResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class CurrentlyPlayingResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private TrackFactory $trackFactory;

    public function __construct(
        TrackFactory $trackFactory
    ) {
        $this->trackFactory = $trackFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        if ($response->getStatusCode() === SpotifyResponseInterface::STATUS_CODE_NO_CONTENT) {
            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        $response = new CurrentlyPlayingResponseBody();

        $response->setTimestamp($data['timestamp'] ?? null);
        $response->setContext($data['context'] ?? null);
        $response->setProgressMs($data['progress_ms'] ?? null);

        $response->setItem(
            $this->trackFactory->create($data['item'])
        );

        $response->setCurrentlyPlayingType($data['currently_playing_type']);
        $response->setActions($data['actions']);
        $response->setIsPlaying($data['is_playing']);

        return $response;
    }
}
