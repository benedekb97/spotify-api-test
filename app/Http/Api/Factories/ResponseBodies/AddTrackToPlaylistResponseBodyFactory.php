<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\AddTrackToPlaylistResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class AddTrackToPlaylistResponseBodyFactory implements ResponseBodyFactoryInterface
{
    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $responseBody = new AddTrackToPlaylistResponseBody();

        $responseBody->setSnapshotId($data['snapshot_id']);

        return $responseBody;
    }
}
