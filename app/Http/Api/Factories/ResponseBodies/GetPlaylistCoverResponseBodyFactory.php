<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\ImageFactory;
use App\Http\Api\Responses\ResponseBodies\GetPlaylistCoverResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class GetPlaylistCoverResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private ImageFactory $imageFactory;

    public function __construct(
        ImageFactory $imageFactory
    ) {
        $this->imageFactory = $imageFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $responseBody = new GetPlaylistCoverResponseBody();

        foreach ($data as $image) {
            $responseBody->addImage(
                $this->imageFactory->create($image)
            );
        }

        return $responseBody;
    }
}
