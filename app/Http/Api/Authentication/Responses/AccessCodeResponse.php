<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Responses;

class AccessCodeResponse
{
    private ?string $code;

    private ?string $error;

    private string $state;

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setError(?string $error): void
    {
        $this->error = $error;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function hasCode(): bool
    {
        return $this->code !== null;
    }

    public function hasError(): bool
    {
        return $this->error !== null;
    }
}
