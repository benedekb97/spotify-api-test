<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Factories\RequestBodies\AddTrackToPlaylistRequestBodyFactory;
use App\Http\Api\Factories\ResponseBodies\AddTrackToPlaylistResponseBodyFactory;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class AddTrackToPlaylistRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT_SCHEMA = 'v1/playlists/%s/tracks';

    private string $playlistId;

    public function __construct(string $playlistId)
    {
        parent::__construct();

        $this->playlistId = $playlistId;
    }

    public function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_PLAYLISTS_MODIFY_PUBLIC,
            SpotifyAuthenticationApiInterface::SCOPE_PLAYLISTS_MODIFY_PRIVATE
        ];
    }

    protected function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT_SCHEMA, $this->playlistId);
    }

    protected function getMethod(): string
    {
        return self::METHOD_POST;
    }

    protected function getExpectedStatusCode(): ?int
    {
        return SpotifyResponseInterface::STATUS_CODE_CREATED;
    }

    protected function validateStatusCode(Response $response): bool
    {
        return true;
    }

    protected function getEvents(): array
    {
        return [

        ];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return AddTrackToPlaylistRequestBodyFactory::class;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return AddTrackToPlaylistResponseBodyFactory::class;
    }
}
