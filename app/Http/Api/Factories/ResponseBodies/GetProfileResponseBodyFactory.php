<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\UserFactory;
use App\Http\Api\Responses\ResponseBodies\GetProfileResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class GetProfileResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private UserFactory $userFactory;

    public function __construct(
        UserFactory $userFactory
    ) {
        $this->userFactory = $userFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $responseBody = new GetProfileResponseBody();

        $responseBody->setUser(
            $this->userFactory->create($data)
        );

        return $responseBody;
    }
}
