<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Events\CreatePlaybackEvent;
use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Factories\ResponseBodies\CurrentlyPlayingResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\CurrentlyPlayingResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class CurrentlyPlayingRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/me/player/currently-playing';

    public function getScopes(): array
    {
        return [SpotifyAuthenticationApiInterface::SCOPE_SPOTIFY_CONNECT_CURRENTLY_PLAYING];
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
        return null;
    }

    protected function validateStatusCode(Response $response): bool
    {
        return in_array(
            $response->getStatusCode(),
            [
                SpotifyResponseInterface::STATUS_CODE_OK,
                SpotifyResponseInterface::STATUS_CODE_NO_CONTENT
            ]
        );
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return CurrentlyPlayingResponseBodyFactory::class;
    }

    protected function getEvents(): array
    {
        return [
            UpdateTracksEvent::class => $this->getTrack(),
        ];
    }

    private function getTrack(): array
    {
        /** @var CurrentlyPlayingResponseBody|null $responseBody */
        $responseBody = $this->getResponse()->getBody();

        if ($responseBody === null) {
            return [null];
        }

        if ($responseBody->getIsPlaying()) {
            return [$responseBody->getItem()];
        }

        return [null];
    }
}
