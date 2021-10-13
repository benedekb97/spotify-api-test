<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\User;

class GetProfileResponseBody implements ResponseBodyInterface
{
    private ?User $user = null;

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
        ];
    }
}
