<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Factories\ResponseBodies\TopTracksResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\TopTracksResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class TopTracksRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/me/top/tracks?limit=50';

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
        return TopTracksResponseBodyFactory::class;
    }

    protected function getEvents(): array
    {
        return [
            UpdateTracksEvent::class => $this->getUpdateTracksEventParameters(),
        ];
    }

    private function getUpdateTracksEventParameters(): array
    {
        /** @var TopTracksResponseBody|null $responseBody */
        $responseBody = $this->getResponse()->getBody();

        if ($responseBody === null) {
            return [null];
        }

        return [$responseBody->getItems()];
    }
}
