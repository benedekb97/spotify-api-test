<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\RecentlyPlayedFactory;
use App\Http\Api\Responses\ResponseBodies\RecentlyPlayedTracksResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class RecentlyPlayedTracksResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private RecentlyPlayedFactory $recentlyPlayedFactory;

    public function __construct(
        RecentlyPlayedFactory $recentlyPlayedFactory
    ) {
        $this->recentlyPlayedFactory = $recentlyPlayedFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $responseBody = new RecentlyPlayedTracksResponseBody();

        $responseBody->setHref($data['href']);
        $responseBody->setCursors($data['cursors']);
        $responseBody->setLimit($data['limit']);
        $responseBody->setNext($data['next']);

        foreach ($data['items'] as $recentlyPlayed) {
            $responseBody->addItem(
                $this->recentlyPlayedFactory->create($recentlyPlayed)
            );
        }

        return $responseBody;
    }
}
