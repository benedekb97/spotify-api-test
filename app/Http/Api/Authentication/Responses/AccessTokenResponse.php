<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Responses;

use App\Http\Api\Authentication\Responses\Entity\Scope;
use DateTimeInterface;

class AccessTokenResponse
{
    private string $accessToken;

    private string $tokenType;

    private Scope $scope;

    private int $expiresIn;

    private string $refreshToken;

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function setTokenType(string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    public function getScope(): Scope
    {
        return $this->scope;
    }

    public function setScope(Scope $scope): void
    {
        $this->scope = $scope;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}
