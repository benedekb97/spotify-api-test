<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Events\UpdatePlaylistsEvent;
use App\Http\Api\Factories\ResponseBodies\GetUserPlaylistsResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\GetUserPlaylistsResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class GetUserPlaylistsRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT_SCHEMA = 'v1/me/playlists?limit=%d&offset=%d';

    private int $offset;

    private int $limit;

    public function __construct(
        int $offset = 0,
        int $limit = 50
    ) {
        parent::__construct();

        $this->offset = $offset;
        $this->limit = $limit;
    }

    public static function getScopes(): array
    {
        return [
            SpotifyAuthenticationApi::SCOPE_PLAYLISTS_READ_PRIVATE,
            SpotifyAuthenticationApi::SCOPE_PLAYLISTS_READ_COLLABORATIVE,
        ];
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            self::ENDPOINT_SCHEMA,
            $this->limit,
            $this->offset
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
            UpdatePlaylistsEvent::class => $this->getUpdatePlaylistsEventParameters()
        ];
    }

    private function getUpdatePlaylistsEventParameters(): array
    {
        /** @var GetUserPlaylistsResponseBody|null $responseBody */
        $responseBody = $this->getResponse()->getBody();

        if (null === $responseBody) {
            return [$this->getUser(), null];
        }

        return [$this->getUser(), $responseBody->getPlaylists()];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return GetUserPlaylistsResponseBodyFactory::class;
    }
}
