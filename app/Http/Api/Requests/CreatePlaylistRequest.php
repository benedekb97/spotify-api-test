<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Factories\RequestBodies\CreatePlaylistRequestBodyFactory;
use App\Http\Api\Factories\ResponseBodies\CreatePlaylistResponseBodyFactory;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class CreatePlaylistRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT_SCHEMA = 'v1/users/%s/playlists';

    private string $userId;

    public function __construct(
        string $userId
    ) {
        parent::__construct();

        $this->userId = $userId;
    }

    public static function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_PLAYLISTS_MODIFY_PRIVATE,
            SpotifyAuthenticationApiInterface::SCOPE_PLAYLISTS_MODIFY_PUBLIC,
            SpotifyAuthenticationApiInterface::SCOPE_PLAYLISTS_READ_PRIVATE,
            SpotifyAuthenticationApiInterface::SCOPE_PLAYLISTS_READ_COLLABORATIVE,
        ];
    }

    protected function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT_SCHEMA, $this->userId);
    }

    protected function getMethod(): string
    {
        return self::METHOD_POST;
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
                SpotifyResponseInterface::STATUS_CODE_CREATED,
            ]
        );
    }

    protected function getEvents(): array
    {
        return [];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return CreatePlaylistRequestBodyFactory::class;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return CreatePlaylistResponseBodyFactory::class;
    }
}
