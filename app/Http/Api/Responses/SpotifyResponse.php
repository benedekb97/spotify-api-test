<?php

declare(strict_types=1);

namespace App\Http\Api\Responses;

use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;

class SpotifyResponse implements SpotifyResponseInterface
{
    private ?ResponseBodyInterface $body = null;

    private ?array $headers = null;

    private ?int $statusCode = null;

    public function hasBody(): bool
    {
        return isset($this->body);
    }

    public function setBody(?ResponseBodyInterface $responseBody): void
    {
        $this->body = $responseBody;
    }

    public function getBody(): ?ResponseBodyInterface
    {
        return $this->body;
    }

    public function setHeaders(?array $headers): void
    {
        $this->headers = $headers;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function isOk(): bool
    {
        return $this->statusCode === self::STATUS_CODE_OK;
    }

    public function isCreated(): bool
    {
        return $this->statusCode === self::STATUS_CODE_CREATED;
    }

    public function isNoContent(): bool
    {
        return $this->statusCode === self::STATUS_CODE_NO_CONTENT;
    }

    public function isUnauthorized(): bool
    {
        return $this->statusCode === self::STATUS_CODE_UNAUTHORIZED;
    }

    public function isUnauthenticated(): bool
    {
        return $this->statusCode === self::STATUS_CODE_UNAUTHENTICATED;
    }

    public function isTooManyRequests(): bool
    {
        return $this->statusCode === self::STATUS_CODE_TOO_MANY_REQUESTS;
    }
}
