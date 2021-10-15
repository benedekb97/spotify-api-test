<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\UserInterface;
use Illuminate\Auth\AuthManager;
use LogicException;

class UserProvider implements UserProviderInterface
{
    private AuthManager $authManager;

    public function __construct(
        AuthManager $authManager
    ) {
        $this->authManager = $authManager;
    }

    public function provide(): UserInterface
    {
        $user = $this->authManager->guard('web')->user();

        if ($user instanceof UserInterface) {
            return $user;
        }

        throw new LogicException('User could not be found!');
    }
}
