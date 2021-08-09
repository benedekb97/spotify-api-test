<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class AddItemToQueueRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    public const ENDPOINT = 'v1/me/player/queue';

    private string $uri;

    public function __construct(
        string $uri
    ) {
        parent::__construct();

        $this->uri = $uri;
    }

    public function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_SPOTIFY_CONNECT_MODIFY_PLAYBACK_STATE,
        ];
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            '%s?uri=%s',
            self::ENDPOINT,
            $this->uri
        );
    }

    protected function getMethod(): string
    {
        return self::METHOD_POST;
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
        return null;
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
