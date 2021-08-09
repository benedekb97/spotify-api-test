<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Factories\RequestBodies\ModifyPlaybackDeviceRequestBodyFactory;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class TransferPlaybackRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/me/player';

    public function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_SPOTIFY_CONNECT_MODIFY_PLAYBACK_STATE
        ];
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function getMethod(): string
    {
        return self::METHOD_PUT;
    }

    protected function getExpectedStatusCode(): ?int
    {
        return SpotifyResponseInterface::STATUS_CODE_NO_CONTENT;
    }

    protected function validateStatusCode(Response $response): bool
    {
        return true;
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return ModifyPlaybackDeviceRequestBodyFactory::class;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return null;
    }

    protected function getEvents(): array
    {
        return [];
    }
}
