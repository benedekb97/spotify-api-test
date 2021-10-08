<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Entities\UserInterface;
use App\Http\Api\Requests\GetUserTracksRequest;
use App\Http\Api\Responses\ResponseBodies\GetUserTracksResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\Synchronizers\UserTracksSynchronizer;
use App\Services\Synchronizers\UserTracksSynchronizerInterface;
use App\Services\User\SpotifyReauthenticationService;
use App\Services\User\SpotifyReauthenticationServiceInterface;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;

class SynchronizeUserTracksJob
{
    use Dispatchable;

    private SpotifyApiInterface $spotifyApi;

    private UserRepositoryInterface $userRepository;

    private SpotifyReauthenticationServiceInterface $spotifyReauthenticationService;

    private UserTracksSynchronizerInterface $userTracksSynchronizer;

    public function __construct(
        SpotifyApi $spotifyApi,
        UserRepositoryInterface $userRepository,
        SpotifyReauthenticationService $spotifyReauthenticationService,
        UserTracksSynchronizer $userTracksSynchronizer
    )
    {
        $this->spotifyApi = $spotifyApi;
        $this->userRepository = $userRepository;
        $this->spotifyReauthenticationService = $spotifyReauthenticationService;
        $this->userTracksSynchronizer = $userTracksSynchronizer;
    }

    public function __invoke()
    {
        $users = $this->userRepository->findAllLoggedInWithSpotify();

        /** @var UserInterface $user */
        foreach ($users as $user) {
            if ($user->needsReauthentication()) {
                $this->spotifyReauthenticationService->reauthenticate($user);
            }

            $tracks = new Collection();

            $initialRequest = new GetUserTracksRequest();

            $initialRequest->setUser($user);
            $initialRequest->setLimit(50);

            $response = $this->spotifyApi->execute($initialRequest);

            if ($response === null) {
                continue;
            }

            /** @var GetUserTracksResponseBody $responseBody */
            $responseBody = $response->getBody();

            $tracks->merge(new Collection($responseBody->getSavedTracks()->toArray()));

            for ($offset = 50; $offset < $responseBody->getTotal(); $offset = $offset + 50) {
                $request = new GetUserTracksRequest();

                $request->setUser($user);
                $request->setOffset($offset);
                $request->setLimit(50);

                $this->spotifyApi->execute($request);
            }

            $this->userTracksSynchronizer->synchronize($user, $tracks);
        }
    }
}
