<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Factories\ResponseBodies\RecentlyPlayedTracksResponseBodyFactory;
use App\Http\Api\Factories\ResponseBodies\TopTracksResponseBodyFactory;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class GetRecentlyPlayedTracksRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/me/player/recently-played';

    public function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_LISTENING_HISTORY_RECENTLY_PLAYED
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

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function requiresRequestBody(): bool
    {
        return false;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return RecentlyPlayedTracksResponseBodyFactory::class;
    }

    public function hasResponseBody(): bool
    {
        return true;
    }
}
