<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Http\Api\Authentication\Factory\Response\AccessCodeResponseFactory;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Requests\GetCurrentProfileRequest;
use App\Http\Api\Responses\ResponseBodies\GetCurrentProfileResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AccessCodeRequest;
use App\Models\Scope;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use JsonSchema\Uri\Retrievers\FileGetContents;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public const SESSION_STATE_KEY = 'spotify.state';

    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private AccessCodeResponseFactory $accessCodeResponseFactory;

    private SpotifyApiInterface $spotifyApi;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        AccessCodeResponseFactory $accessCodeResponseFactory,
        SpotifyApi $spotifyApi
    )
    {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->accessCodeResponseFactory = $accessCodeResponseFactory;
        $this->spotifyApi = $spotifyApi;
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

            $getCurrentProfileRequest = new GetCurrentProfileRequest();

            $getCurrentProfileRequest->setAccessToken($accessTokenResponse->getAccessToken());

            /** @var GetCurrentProfileResponseBody|null $userDetails */
            $userDetails = $this->spotifyApi->execute($getCurrentProfileRequest)->getBody();

            if ($userDetails === null) {
                return new JsonResponse(
                    [
                        'error' => 'Could not fetch user details.'
                    ]
                );
            }

            /** @var User $user */
            $user = User::where('email', $userDetails->getUser()->getEmail())->first();

            if ($user === null) {
                $user = new User();

                $user->email = $userDetails->getUser()->getEmail();
                $user->name = $userDetails->getUser()->getDisplayName();
            }

            $user->spotify_access_token = $accessTokenResponse->getAccessToken();
            $user->spotify_refresh_token = $accessTokenResponse->getRefreshToken();
            $user->spotify_access_token_expiry = $tokenExpiry;
            $user->spotify_id = $userDetails->getUser()->getId();

            $user->save();

            Auth::login($user);

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
