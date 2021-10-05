<?php

declare(strict_types=1);

namespace App\Services\Generators;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;
use App\Factories\PlaylistFactoryInterface;
use App\Http\Api\Requests\AddTrackToPlaylistRequest;
use App\Http\Api\Requests\CreatePlaylistRequest;
use App\Http\Api\Responses\ResponseBodies\CreatePlaylistResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Services\Compilers\PlaylistRecommendedTrackCompiler;
use App\Services\Compilers\PlaylistRecommendedTrackCompilerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use LogicException;

class RecommendedPlaylistGenerator implements RecommendedPlaylistGeneratorInterface
{
    private PlaylistRecommendedTrackCompilerInterface $playlistRecommendedTrackCompiler;

    private PlaylistFactoryInterface $playlistFactory;

    private SpotifyApiInterface $spotifyApi;

    public function __construct(
        PlaylistRecommendedTrackCompiler $playlistRecommendedTrackCompiler,
        PlaylistFactoryInterface         $playlistFactory,
        SpotifyApi                       $spotifyApi
    ) {
        $this->playlistRecommendedTrackCompiler = $playlistRecommendedTrackCompiler;
        $this->playlistFactory = $playlistFactory;
        $this->spotifyApi = $spotifyApi;
    }

    public function generate(
        PlaylistInterface $playlist,
        int               $trackCount = self::DEFAULT_TRACK_COUNT
    ): PlaylistInterface
    {
        $user = $playlist->getLocalUser();

        if (!$user instanceof UserInterface || !$user->isLoggedInWithSpotify()) {
            throw new LogicException('The playlist\'s owner is not logged in!');
        }

        $recommendedTracks = $this->playlistRecommendedTrackCompiler->compile($playlist);

        $tracks = new ArrayCollection();

        $count = 0;

        foreach ($recommendedTracks as $track) {
            /** @var TrackInterface $track */
            if (!$playlist->hasTrack($track = $track['track'])) {
                $tracks->add($track);

                $count++;
            }

            if ($count === $trackCount) {
                break;
            }
        }

        $newPlaylist = $this->createPlaylist($user, $playlist);

        $trackUris = $tracks->map(fn (TrackInterface $t) => $t->getUri())->toArray();

        $this->addTracks($newPlaylist, $user, $trackUris);

        return $newPlaylist;
    }

    public function createPlaylist(UserInterface $user, PlaylistInterface $playlist): PlaylistInterface
    {
        $request = new CreatePlaylistRequest($user->getSpotifyId());
        $request->setUser($user);

        $response = $this->spotifyApi->execute(
            $request,
            $this->getPlaylistName($playlist),
            false,
            false,
            $this->getPlaylistName($playlist)
        );

        /** @var CreatePlaylistResponseBody $responseBody */
        $responseBody = $response->getBody();

        return $this->playlistFactory->createFromSpotifyEntity($responseBody->getPlaylist());
    }

    private function getPlaylistName(PlaylistInterface $playlist): string
    {
        return 'Recommended songs for ' . $playlist->getName();
    }

    public function addTracks(PlaylistInterface $playlist, UserInterface $user, array $trackUris): void
    {
        $this->spotifyApi->execute(
            (new AddTrackToPlaylistRequest($playlist->getId()))->setUser($user),
            $trackUris
        );
    }
}
