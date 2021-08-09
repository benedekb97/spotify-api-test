<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Factories\ResponseBodies\GetCurrentProfileResponseBodyFactory;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class GetCurrentProfileRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/me';

    public function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_USERS_READ_PRIVATE,
            SpotifyAuthenticationApiInterface::SCOPE_USERS_READ_EMAIL,
        ];
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function getMethod(): string
    {
        return self::METHOD_GET;
    }

    protected function getExpectedStatusCode(): ?int
    {
        return SpotifyResponseInterface::STATUS_CODE_OK;
    }

    protected function validateStatusCode(Response $response): bool
    {
        return true;
    }

    protected function getEvents(): array
    {
        return [];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return GetCurrentProfileResponseBodyFactory::class;
    }
}
