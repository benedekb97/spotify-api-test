<?php

declare(strict_types=1);

namespace App\Http\Api\Validators;

use App\Http\Api\Requests\SpotifyRequestInterface;
use App\Models\Scope;
use App\Models\User;
use LogicException;

class UserRequestScopeValidator
{
    public function validate(User $user, SpotifyRequestInterface $spotifyRequest): bool
    {
        foreach ($spotifyRequest->getScopes() as $scopeName) {
            $scope = Scope::all()->where('name', $scopeName)->first();

            if ($scope === null) {
                throw new LogicException(
                    sprintf('Could not find scope with name %s.', $scopeName)
                );
            }

            if (!$user->scopes->contains($scope)) {
                return false;
            }
        }

        return true;
    }
}
