<?php

declare(strict_types=1);

namespace App\Http\Api\Responses;

use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;

interface SpotifyResponseInterface
{
    public const STATUS_CODE_OK = 200;
    public const STATUS_CODE_CREATED = 201;
    public const STATUS_CODE_NO_CONTENT = 204;
    public const STATUS_CODE_UNAUTHENTICATED = 401;
    public const STATUS_CODE_UNAUTHORIZED = 403;
    public const STATUS_CODE_TOO_MANY_REQUESTS = 429;

    public function hasBody(): bool;

    public function setBody(?ResponseBodyInterface $responseBody): void;

    public function getBody(): ?ResponseBodyInterface;

    public function setHeaders(?array $headers): void;

    public function getHeaders(): ?array;

    public function getStatusCode(): ?int;

    public function isOk(): bool;

    public function isCreated(): bool;

    public function isNoContent(): bool;

    public function isUnauthenticated(): bool;

    public function isUnauthorized(): bool;

    public function isTooManyRequests(): bool;
}
