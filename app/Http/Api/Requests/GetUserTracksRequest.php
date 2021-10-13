<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Events\UpdateUserTracksEvent;
use App\Http\Api\Factories\ResponseBodies\GetUserTracksResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\GetUserTracksResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class GetUserTracksRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT_SCHEMA = 'v1/me/tracks?limit=%d&offset=%d';
    private const DEFAULT_LIMIT = 20;
    private const DEFAULT_OFFSET = 0;

    private int $limit = self::DEFAULT_LIMIT;

    private int $offset = self::DEFAULT_OFFSET;

    public function setLimit(int $limit): void
    {
        $this->limit = max(min($limit, 50), 1);
    }

    public function setOffset(int $offset): void
    {
        $this->offset = max($offset, 0);
    }

    public function getScopes(): array
    {
        return [SpotifyAuthenticationApiInterface::SCOPE_LIBRARY_READ];
    }

    protected function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT_SCHEMA, $this->limit, $this->offset);
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
        return [
            UpdateUserTracksEvent::class => $this->getUpdateUserTracksEventParameters(),
        ];
    }

    private function getUpdateUserTracksEventParameters(): array
    {
        /** @var GetUserTracksResponseBody $responseBody */
        $responseBody = $this->getResponse()->getBody();

        return [
            $this->getUser(),
            $responseBody->getSavedTracks()
        ];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return GetUserTracksResponseBodyFactory::class;
    }
}
