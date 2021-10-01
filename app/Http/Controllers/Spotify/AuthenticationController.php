<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Entities\ScopeInterface;
use App\Entities\UserInterface;
use App\Factories\UserFactoryInterface;
use App\Http\Api\Authentication\Factory\Response\AccessCodeResponseFactory;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Requests\GetCurrentProfileRequest;
use App\Http\Api\Responses\ResponseBodies\GetCurrentProfileResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AccessCodeRequest;
use App\Repositories\ScopeRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
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

    private SpotifyApiInterface $spotifyApi;

    private UserRepositoryInterface $userRepository;

    private UserFactoryInterface $userFactory;

    private ScopeRepositoryInterface $scopeRepository;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        AccessCodeResponseFactory $accessCodeResponseFactory,
        SpotifyApi $spotifyApi,
        UserRepositoryInterface $userRepository,
        UserFactoryInterface $userFactory,
        EntityManager $entityManager,
        ScopeRepositoryInterface $scopeRepository
    )
    {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->accessCodeResponseFactory = $accessCodeResponseFactory;
        $this->spotifyApi = $spotifyApi;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->scopeRepository = $scopeRepository;

        parent::__construct($entityManager);
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

            /** @var UserInterface $user */
            $user = $this->userRepository->findOneByEmail($userDetails->getUser()->getEmail());

            if ($user === null) {
                /** @var \App\Entities\User $user */
                $user = $this->userFactory->createNew();

                $user->setEmail($userDetails->getUser()->getEmail());
                $user->setName($userDetails->getUser()->getDisplayName());
            }

            $user->setSpotifyAccessToken($accessTokenResponse->getAccessToken());
            $user->setSpotifyRefreshToken($accessTokenResponse->getRefreshToken());
            $user->setSpotifyAccessTokenExpiry($tokenExpiry);
            $user->setSpotifyId($userDetails->getUser()->getId());

            foreach ($accessTokenResponse->getScope()->getScope() as $scopeName) {
                /** @var ScopeInterface $scope */
                $scope = $this->scopeRepository->findOneByName($scopeName);

                if ($scope === null) {
                    throw new LogicException(
                        sprintf('Could not find scope %s in database!', $scopeName)
                    );
                }

                $user->addScope($scope);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            Auth::login($user);

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
