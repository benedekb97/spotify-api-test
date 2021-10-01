<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Entities\ScopeInterface;
use App\Entities\UserInterface;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Repositories\ScopeRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Closure;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;
use LogicException;

class ReauthenticateSpotifyMiddleware
{
    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private UserRepositoryInterface $userRepository;

    private ScopeRepositoryInterface $scopeRepository;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        UserRepositoryInterface $userRepository,
        ScopeRepositoryInterface $scopeRepository
    ) {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->userRepository = $userRepository;
        $this->scopeRepository = $scopeRepository;
    }

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            /** @var UserInterface $user */
            $user = Auth::user();

            if (
                $user->getSpotifyRefreshToken() !== null
                && (new DateTime()) >= $user->getSpotifyAccessTokenExpiry()
            ) {
                $this->reauthenticate($user->getSpotifyRefreshToken());

                $this->userRepository->add($user);
            }
        }

        return $next($request);
    }

    private function reauthenticate(string $refreshToken): void
    {
        $refreshedAccessTokenResponse = $this->spotifyAuthenticationApi->refreshAccessToken($refreshToken);

        $tokenExpiry = (new DateTime())
            ->add(new DateInterval(sprintf('PT%sS', $refreshedAccessTokenResponse->getExpiresIn())));

        /** @var UserInterface $user */
        $user = Auth::user();

        $user->setSpotifyAccessToken($refreshedAccessTokenResponse->getAccessToken());
        $user->setSpotifyAccessTokenExpiry($tokenExpiry);

        foreach ($refreshedAccessTokenResponse->getScope()->getScope() as $scopeName) {
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
            if (!$refreshedAccessTokenResponse->getScope()->getScope()->contains($userScope->getName())) {
                $user->removeScope($userScope);
            }
        }
    }
}
