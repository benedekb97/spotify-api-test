<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;
use App\Factories\TrackAssociationFactoryInterface;
use App\Http\Api\Requests\GetRecommendationsRequest;
use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use App\Http\Api\Responses\ResponseBodies\RecommendationsResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Repositories\TrackRepositoryInterface;
use Illuminate\Cache\Repository;
use Illuminate\Support\Arr;

class RecommendedTrackProvider implements RecommendedTrackProviderInterface
{
    private SpotifyApiInterface $spotifyApi;

    private TrackRepositoryInterface $trackRepository;

    public function __construct(
        SpotifyApi $spotifyApi,
        TrackRepositoryInterface $trackRepository
    ) {
        $this->spotifyApi = $spotifyApi;
        $this->trackRepository = $trackRepository;
    }

    public function provideForUserWithTrack(
        TrackInterface $track,
        UserInterface $user,
        ?int $max = null
    ): array
    {
        $request = new GetRecommendationsRequest(null, null, [$track->getId()]);

        $request->setUser($user);

        $response = $this->spotifyApi->execute($request);

        /** @var RecommendationsResponseBody $responseBody */
        $responseBody = $response->getBody();

        $count = 0;
        $tracks = [];

        $recommendedTracks = $responseBody->getTracks();

        /** @var Track $recommendedTrack */
        foreach ($recommendedTracks as $recommendedTrack) {
            if (isset($max) && $count === $max) {
                return $tracks;
            }

            /** @var TrackInterface $recommendedTrack */
            $recommendedTrack = $this->trackRepository->find($recommendedTrack->getId());

            if (!in_array($recommendedTrack, $tracks)) {
                $tracks[] = $recommendedTrack;
            }

            $count++;
        }

        return $tracks;
    }
}
