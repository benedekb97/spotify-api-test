<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Models\Scope;
use App\Models\User;
use Closure;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;
use LogicException;

class ReauthenticateSpotifyMiddleware
{
    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi
    ) {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
    }

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if (
                isset($user->spotify_refresh_token)
                && (new DateTime()) >= (new DateTime($user->spotify_access_token_expiry))
            ) {

                $this->reauthenticate($user->spotify_refresh_token);
            }
        }

        return $next($request);
    }

    private function reauthenticate(string $refreshToken): void
    {
        $refreshedAccessTokenResposne = $this->spotifyAuthenticationApi->refreshAccessToken($refreshToken);

        $tokenExpiry = (new DateTime())
            ->add(new DateInterval(sprintf('PT1%sS', $refreshedAccessTokenResposne->getExpiresIn())));

        /** @var User $user */
        $user = Auth::user();

        $user->spotify_access_token = $refreshedAccessTokenResposne->getAccessToken();
        $user->spotify_access_token_expiry = $tokenExpiry->format('Y-m-d H:i:s');

        $user->save();

        foreach ($refreshedAccessTokenResposne->getScope()->getScope() as $scopeName) {
            $scope = Scope::all()->where('name', $scopeName)->first();

            if ($scope === null) {
                throw new LogicException(
                    sprintf('Scope could not be found with name %s.', $scopeName)
                );
            }

            if (!$user->scopes->contains($scope)) {
                $user->scopes()->attach($scope);
            }
        }

        foreach ($user->scopes as $userScope) {
            if (!$refreshedAccessTokenResposne->getScope()->getScope()->contains($userScope->name)) {
                $user->scopes()->detach($userScope);
            }
        }
    }
}
