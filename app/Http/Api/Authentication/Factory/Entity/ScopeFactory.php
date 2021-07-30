<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Factory\Entity;

use App\Http\Api\Authentication\Responses\Entity\Scope;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;

class ScopeFactory
{
    public function create(string $scope): Scope
    {
        $object = new Scope();

        $scopes = explode(' ', trim($scope));

        foreach ($scopes as $scopeText) {
            if (array_key_exists($scopeText,SpotifyAuthenticationApiInterface::SCOPE_ENABLED_MAP)) {
                $object->addScope($scopeText);
            }
        }

        return $object;
    }
}
