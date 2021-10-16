<?php

declare(strict_types=1);

namespace App\Services\Synchronizers;

use App\Entities\Spotify\AlbumInterface;
use App\Http\Api\Requests\GetAlbumTracksRequest;
use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Services\Providers\Spotify\AlbumProvider;
use App\Services\Providers\Spotify\AlbumProviderInterface;
use App\Services\Providers\Spotify\TrackProvider;
use App\Services\Providers\Spotify\TrackProviderInterface;
use App\Services\Providers\UserProvider;
use App\Services\Providers\UserProviderInterface;
use App\Services\User\SpotifyReauthenticationService;
use App\Services\User\SpotifyReauthenticationServiceInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class AlbumSynchronizer implements AlbumSynchronizerInterface
{
    private SpotifyApiInterface $spotifyApi;

    private SpotifyReauthenticationServiceInterface $spotifyReauthenticationService;

    private UserProviderInterface $userProvider;

    private TrackProviderInterface $trackProvider;

    private EntityManagerInterface $entityManager;

    public function __construct(
        SpotifyApi $spotifyApi,
        SpotifyReauthenticationService $spotifyReauthenticationService,
        UserProvider $userProvider,
        TrackProvider $trackProvider,
        EntityManager $entityManager
    )
    {
        $this->spotifyApi = $spotifyApi;
        $this->spotifyReauthenticationService = $spotifyReauthenticationService;
        $this->userProvider = $userProvider;
        $this->trackProvider = $trackProvider;
        $this->entityManager = $entityManager;
    }

    public function synchronize(AlbumInterface $album): AlbumInterface
    {
        $request = new GetAlbumTracksRequest();

        $request->setUser($user = $this->userProvider->provide());
        $request->setAlbumId($album->getId());

        if ($user->needsReauthentication()) {
            $this->spotifyReauthenticationService->reauthenticate($user);
        }

        $response = $this->spotifyApi->execute($request);

        if ($response->hasBody()) {
            /** @var Track $track */
            foreach ($response->getBody()->getItems() as $track) {
                $track = $this->trackProvider->provide($track);

                $album->addTrack($track);
            }
        }

        $this->entityManager->persist($album);

        return $album;
    }
}
