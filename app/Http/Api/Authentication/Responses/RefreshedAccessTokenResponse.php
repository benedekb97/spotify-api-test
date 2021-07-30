<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Responses;

use App\Http\Api\Authentication\Responses\Entity\Scope;

class RefreshedAccessTokenResponse
{
    private string $accessToken;

    private string $tokenType;

    private Scope $scope;

    private int $expiresIn;

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
}
