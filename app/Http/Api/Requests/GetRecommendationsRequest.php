<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Factories\ResponseBodies\RecommendationsResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\RecommendationsResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class GetRecommendationsRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/recommendations';

    private ?array $seedTracks;

    private ?array $seedGenres;

    private ?array $seedArtists;

    public function __construct(
        ?array $artists,
        ?array $genres,
        ?array $tracks
    ) {
        parent::__construct();

        $this->seedArtists = $artists;
        $this->seedGenres = $genres;
        $this->seedTracks = $tracks;
    }

    public function getScopes(): array
    {
        return [];
    }

    protected function getEndpoint(): string
    {
        $queryString = '';

        if (isset($this->seedArtists)) {
            if (empty($queryString)) {
                $queryString = '?seed_artists=' . implode(',', $this->seedArtists);
            } else {
                $queryString .= '&seed_artists=' . implode(',', $this->seedArtists);
            }
        }

        if (isset($this->seedGenres)) {
            if (empty($queryString)) {
                $queryString = '?seed_genres=' . implode(',', $this->seedGenres);
            } else {
                $queryString .= '&seed_genres=' . implode(',', $this->seedGenres);
            }
        }

        if (isset($this->seedTracks)) {
            if (empty($queryString)) {
                $queryString = '?seed_tracks=' . implode(',', $this->seedTracks);
            } else {
                $queryString .= '&seed_tracks=' . implode(',', $this->seedTracks);
            }
        }

        return sprintf(
            '%s%s',
            self::ENDPOINT,
            $queryString
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

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function requiresRequestBody(): bool
    {
        return false;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return RecommendationsResponseBodyFactory::class;
    }

    public function hasResponseBody(): bool
    {
        return true;
    }

    protected function getEvents(): array
    {
        return [
            UpdateTracksEvent::class => $this->getUpdateTracksEventParameters(),
        ];
    }

    private function getUpdateTracksEventParameters(): array
    {
        /** @var RecommendationsResponseBody|null $responseBody */
        $responseBody = $this->getResponse()->getBody();

        if ($responseBody === null) {
            return [null];
        }

        return [$responseBody->getTracks()];
    }
}
