<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use App\Http\Api\Responses\ResponseBodies\Entity\User;
use Illuminate\Foundation\Events\Dispatchable;

class UpdateProfileEvent
{
    use Dispatchable;

    private User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
