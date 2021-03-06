<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Events\UpdateProfileEvent;
use App\Http\Api\Factories\ResponseBodies\GetProfileResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\GetProfileResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;

class GetProfileRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT = 'v1/me';

    public static function getScopes(): array
    {
        return [
            SpotifyAuthenticationApiInterface::SCOPE_USERS_READ_EMAIL,
            SpotifyAuthenticationApiInterface::SCOPE_USERS_READ_PRIVATE,
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
            UpdateProfileEvent::class => $this->getUpdateProfileEventParameters(),
        ];
    }

    private function getUpdateProfileEventParameters(): array
    {
        /** @var GetProfileResponseBody $responseBody */
        $responseBody = $this->getResponse()->getBody();

        return [$responseBody->getUser()];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return GetProfileResponseBodyFactory::class;
    }
}
