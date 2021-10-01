<?php

declare(strict_types=1);

namespace App\Http\Api\Validators;

use App\Entities\UserInterface;
use App\Http\Api\Requests\SpotifyRequestInterface;
use App\Repositories\ScopeRepositoryInterface;
use LogicException;

class UserRequestScopeValidator
{
    private ScopeRepositoryInterface $scopeRepository;

    public function __construct(
        ScopeRepositoryInterface $scopeRepository
    ) {
        $this->scopeRepository = $scopeRepository;
    }

    public function validate(UserInterface $user, SpotifyRequestInterface $spotifyRequest): bool
    {
        foreach ($spotifyRequest->getScopes() as $scopeName) {
            $scope = $this->scopeRepository->findOneByName($scopeName);

            if ($scope === null) {
                throw new LogicException(
                    sprintf('Could not find scope with name %s.', $scopeName)
                );
            }

            if (!$user->hasScope($scope)) {
                return false;
            }
        }

        return true;
    }
}
