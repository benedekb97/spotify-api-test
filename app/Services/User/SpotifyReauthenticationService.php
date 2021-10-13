<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Entities\ScopeInterface;
use App\Entities\UserInterface;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Repositories\ScopeRepositoryInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use LogicException;

class SpotifyReauthenticationService implements SpotifyReauthenticationServiceInterface
{
    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private ScopeRepositoryInterface $scopeRepository;

    private EntityManager $entityManager;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        ScopeRepositoryInterface $scopeRepository,
        EntityManager $entityManager
    ) {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->scopeRepository = $scopeRepository;
        $this->entityManager = $entityManager;
    }

    public function reauthenticate(UserInterface $user): void
    {
        $refreshedAccessTokenResponse = $this->spotifyAuthenticationApi->refreshAccessToken($user->getSpotifyRefreshToken());

        $tokenExpiry = (new DateTime())
            ->add(new DateInterval(sprintf('PT%sS', $refreshedAccessTokenResponse->getExpiresIn())));

        $user->setSpotifyAccessToken($refreshedAccessTokenResponse->getAccessToken());
        $user->setSpotifyAccessTokenExpiry($tokenExpiry);

        $this->updateScopes($user, $refreshedAccessTokenResponse->getScope()->getScope());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function updateScopes(UserInterface $user, Collection $scopes): void
    {
        foreach ($scopes as $scopeName) {
            $scope = $this->scopeRepository->findOneByName($scopeName);

            if ($scope === null) {
                throw new LogicException(
                    sprintf('Scope could not be found with name %s.', $scopeName)
                );
            }

            $user->addScope($scope);
        }

        /** @var ScopeInterface $userScope */
        foreach ($user->getScopes() as $userScope) {
            if (!$scopes->contains($userScope->getName())) {
                $user->removeScope($userScope);
            }
        }
    }
}
