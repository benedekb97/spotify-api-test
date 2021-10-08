<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Entities\UserInterface;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Requests\GetUserPlaylistsRequest;
use App\Http\Api\Responses\ResponseBodies\GetUserPlaylistsResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\User\SpotifyReauthenticationService;
use App\Services\User\SpotifyReauthenticationServiceInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SynchronizePlaylistsJob
{
    use Dispatchable;

    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private SpotifyApiInterface $spotifyApi;

    private UserRepositoryInterface $userRepository;

    private EntityManager $entityManager;

    private SpotifyReauthenticationServiceInterface $spotifyReauthenticationService;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        SpotifyApi $spotifyApi,
        UserRepositoryInterface $userRepository,
        EntityManager $entityManager,
        SpotifyReauthenticationService $spotifyReauthenticationService
    ) {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->spotifyApi = $spotifyApi;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->spotifyReauthenticationService = $spotifyReauthenticationService;
    }

    public function __invoke(): void
    {
        $users = $this->userRepository->findAllLoggedInWithSpotify();

        /** @var UserInterface $user */
        foreach ($users as $user) {
            Log::info('Synchronizing playlists for user ' . $user->getId());

            if ($user->needsReauthentication()) {
                $this->spotifyReauthenticationService->reauthenticate($user);
            }

            $offset = 0;

            do {
                if (isset($responseBody)) {
                    $offset += $responseBody->getLimit();
                }

                $response = $this->spotifyApi->execute(
                    (new GetUserPlaylistsRequest($offset))->setUser($user)
                );

                /** @var GetUserPlaylistsResponseBody $responseBody */
                $responseBody = $response->getBody();
            } while ($responseBody->getLimit() + $offset < $responseBody->getTotal());
        }

        $this->entityManager->flush();

        Log::info('Finished synchronizing playlists');
    }
}
