<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Factory\Response;

use App\Http\Api\Authentication\Factory\Response\Entity\ScopeFactory;
use App\Http\Api\Authentication\Responses\RefreshedAccessTokenResponse;

class RefreshedAccessTokenResponseFactory
{
    private ScopeFactory $scopeFactory;

    public function __construct(
        ScopeFactory $scopeFactory
    ) {
        $this->scopeFactory = $scopeFactory;
    }

    public function create(array $response): RefreshedAccessTokenResponse
    {
        $object = new RefreshedAccessTokenResponse();

        $object->setAccessToken($response['access_token']);
        $object->setTokenType($response['token_type']);
        $object->setExpiresIn((int)$response['expires_in']);

        $object->setScope(
            $this->scopeFactory->create($response['scope'])
        );

        return $object;
    }
}
