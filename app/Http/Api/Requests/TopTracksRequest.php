<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Factories\ResponseBodies\TopTracksResponseBodyFactory;
use App\Http\Api\Responses\SpotifyResponseInterface;

class TopTracksRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/me/top/tracks';

    public function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_LISTENING_HISTORY_TOP_PLAYED
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

    protected function getExpectedStatusCode(): int
    {
        return SpotifyResponseInterface::STATUS_CODE_OK;
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
        return TopTracksResponseBodyFactory::class;
    }

    public function hasResponseBody(): bool
    {
        return true;
    }
}
