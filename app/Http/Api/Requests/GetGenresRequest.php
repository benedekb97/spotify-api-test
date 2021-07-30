<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Factories\ResponseBodies\GenresResponseBodyFactory;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class GetGenresRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/recommendations/available-genre-seeds';

    public function getScopes(): array
    {
        return [];
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
        return GenresResponseBodyFactory::class;
    }

    public function hasResponseBody(): bool
    {
        return true;
    }
}
