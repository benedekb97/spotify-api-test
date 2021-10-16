<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class UploadPlaylistCoverRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT_SCHEMA = 'v1/playlists/%s/images';

    private string $playlistId;

    public function __construct(
        string $playlistId
    ){
        parent::__construct();

        $this->playlistId = $playlistId;
    }

    public static function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_UGC_IMAGE_UPLOAD,
            SpotifyAuthenticationApiInterface::SCOPE_PLAYLISTS_MODIFY_PRIVATE,
            SpotifyAuthenticationApiInterface::SCOPE_PLAYLISTS_MODIFY_PUBLIC,
        ];
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
        return self::METHOD_PUT;
    }

    protected function getExpectedStatusCode(): ?int
    {
        return SpotifyResponseInterface::STATUS_CODE_ACCEPTED;
    }

    protected function validateStatusCode(Response $response): bool
    {
        return true;
    }

    protected function getEvents(): array
    {
        return [];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return null;
    }
}
