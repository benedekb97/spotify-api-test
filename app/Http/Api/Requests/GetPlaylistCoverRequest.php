<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Events\UpdatePlaylistCoverEvent;
use App\Http\Api\Factories\ResponseBodies\GetPlaylistCoverResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\GetPlaylistCoverResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class GetPlaylistCoverRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT_SCHEMA = 'v1/playlists/%s/images';

    private string $playlistId;

    public function __construct(
        string $playlistId
    ) {
        parent::__construct();

        $this->playlistId = $playlistId;
    }

    public static function getScopes(): array
    {
        return [];
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            self::ENDPOINT_SCHEMA,
            $this->playlistId
        );
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
            UpdatePlaylistCoverEvent::class => $this->getUpdatePlaylistCoverEventParameters(),
        ];
    }

    private function getUpdatePlaylistCoverEventParameters(): array
    {
        /** @var GetPlaylistCoverResponseBody $responseBody */
        $responseBody = $this->getResponse()->getBody();

        return [
            $this->playlistId,
            $responseBody->getImages()
        ];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return GetPlaylistCoverResponseBodyFactory::class;
    }
}
