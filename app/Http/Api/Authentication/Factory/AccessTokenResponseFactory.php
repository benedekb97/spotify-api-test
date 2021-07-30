<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Factory;

use App\Http\Api\Authentication\Factory\Entity\ScopeFactory;
use App\Http\Api\Authentication\Responses\AccessTokenResponse;

class AccessTokenResponseFactory
{
    private ScopeFactory $scopeFactory;

    public function __construct(
        ScopeFactory $scopeFactory
    ) {
        $this->scopeFactory = $scopeFactory;
    }

    public function create(array $response): AccessTokenResponse
    {
        $object = new AccessTokenResponse();

        $object->setAccessToken($response['access_token']);
        $object->setExpiresIn((int)$response['expires_in']);
        $object->setTokenType($response['token_type']);
        $object->setRefreshToken($response['refresh_token']);

        $object->setScope(
            $this->scopeFactory->create($response['scope'])
        );

        return $object;
    }
}
