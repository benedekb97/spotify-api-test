<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Http\Api\Authentication\Factory\Response\AccessCodeResponseFactory;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AccessCodeRequest;
use App\Models\Scope;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public const SESSION_STATE_KEY = 'spotify.state';

    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private AccessCodeResponseFactory $accessCodeResponseFactory;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        AccessCodeResponseFactory $accessCodeResponseFactory
    )
    {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->accessCodeResponseFactory = $accessCodeResponseFactory;
    }

    public function redirect(): Response
    {
        return $this->spotifyAuthenticationApi->redirect();
    }

    public function callback(AccessCodeRequest $request): Response
    {
        $accessCodeResponse = $this->accessCodeResponseFactory->create($request);

        $state = Session::get(self::SESSION_STATE_KEY);

        if ($accessCodeResponse->getState() !== $state) {
            return new JsonResponse(
                [
                    'error' => 'State provided by spotify redirect does not match state stored in session. Aborting.'
                ]
            );
        }

        if ($accessCodeResponse->hasCode()) {
            $accessTokenResponse = $this->spotifyAuthenticationApi->getAccessToken($accessCodeResponse->getCode());

            $tokenExpiry = (new DateTime())
                ->add(new DateInterval(sprintf('PT%sS', $accessTokenResponse->getExpiresIn())));

            /** @var User $user */
            $user = Auth::user();

            $user->spotify_access_token = $accessTokenResponse->getAccessToken();
            $user->spotify_refresh_token = $accessTokenResponse->getRefreshToken();
            $user->spotify_access_token_expiry = $tokenExpiry;

            $user->save();

            foreach ($accessTokenResponse->getScope()->getScope() as $scopeName) {
                $scope = Scope::all()->where('name', $scopeName)->first();

                if ($scope === null) {
                    throw new LogicException(
                        sprintf('Could not find scope %s in database!', $scopeName)
                    );
                }

                $user->scopes()->attach($scope);
            }

            return new RedirectResponse(route('dashboard.index'));
        }

        if ($accessCodeResponse->hasError()) {
            return new JsonResponse(
                [
                    'error' => $accessCodeResponse->getError(),
                ]
            );
        }

        return new JsonResponse(
            [
                'error' => 'Unknown error occurred. Aborting.'
            ]
        );
    }
}
