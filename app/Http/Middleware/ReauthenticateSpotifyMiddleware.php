<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Entities\ScopeInterface;
use App\Entities\UserInterface;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Repositories\ScopeRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\User\SpotifyReauthenticationService;
use App\Services\User\SpotifyReauthenticationServiceInterface;
use Closure;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;
use LogicException;

class ReauthenticateSpotifyMiddleware
{
    private SpotifyReauthenticationServiceInterface $spotifyReauthenticationService;

    public function __construct(
        SpotifyReauthenticationService $spotifyReauthenticationService
    ) {
        $this->spotifyReauthenticationService = $spotifyReauthenticationService;
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
                $this->spotifyReauthenticationService->reauthenticate($user);
            }
        }

        return $next($request);
    }
}
