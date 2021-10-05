<?php

declare(strict_types=1);

namespace App\Services\Compilers;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\PlaylistTrackInterface;
use App\Services\Providers\PlaylistRecommendedTrackProvider;
use App\Services\Providers\PlaylistRecommendedTrackProviderInterface;

class PlaylistRecommendedTrackCompiler implements PlaylistRecommendedTrackCompilerInterface
{
    private PlaylistRecommendedTrackProviderInterface $playlistRecommendedTrackProvider;

    public function __construct(
        PlaylistRecommendedTrackProvider $playlistRecommendedTrackProvider
    ) {
        $this->playlistRecommendedTrackProvider = $playlistRecommendedTrackProvider;
    }

    public function compile(PlaylistInterface $playlist): array
    {
        $recommendedTracks = $playlist->getRecommendedTracks();

        if (empty($recommendedTracks)) {
            $recommendedTracks = $this->playlistRecommendedTrackProvider->provide(
                $playlist,
                $playlist->getLocalUser()
            );
        }

        /** @var PlaylistTrackInterface $playlistTrack */
        foreach ($playlist->getPlaylistTracks() as $playlistTrack) {
            $tracks = $playlistTrack->getTrack()->getRecommendedTracks();

            $tracks = array_slice($tracks, 0, 5);

            foreach ($tracks as $id => $track) {
                if (!array_key_exists($id, $recommendedTracks)) {
                    $recommendedTracks[$id] = [
                        'count' => 1,
                        'track' => $track['track'],
                    ];
                } else {
                    $recommendedTracks[$id]['count']++;
                }
            }
        }

        uasort($recommendedTracks, static function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        return $recommendedTracks;
    }
}
