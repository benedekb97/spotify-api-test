<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Factories\ResponseBodies\GetAlbumTracksResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\GetAlbumTracksResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

/**
 * @method GetAlbumTracksResponseBody getBody()
 */
class GetAlbumTracksRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT_SCHEMA = 'v1/albums/%s/tracks?limit=50';

    private ?string $albumId = null;

    public function setAlbumId(string $albumId): void
    {
        $this->albumId = $albumId;
    }

    public function getScopes(): array
    {
        return [];
    }

    protected function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT_SCHEMA, $this->albumId);
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
            UpdateTracksEvent::class => $this->getUpdateTracksEventParameters()
        ];
    }

    private function getUpdateTracksEventParameters(): array
    {
        /** @var GetAlbumTracksResponseBody $responseBody */
        $responseBody = $this->getResponse()->getBody() ?? null;

        if (!$responseBody instanceof ResponseBodyInterface) {
            return [null];
        }

        return [$responseBody->getItems()];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return GetAlbumTracksResponseBodyFactory::class;
    }
}
