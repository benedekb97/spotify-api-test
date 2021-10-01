<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\PlaylistTrackInterface;
use App\Entities\Spotify\TrackInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack;

class PlaylistTrackFactory extends EntityFactory implements PlaylistTrackFactoryInterface
{
    public function createFromSpotifyEntity(
        PlaylistTrack $entity,
        TrackInterface $track,
        PlaylistInterface $playlist
    ): PlaylistTrackInterface
    {
        /** @var PlaylistTrackInterface $playlistTrack */
        $playlistTrack = $this->createnew();

        $playlistTrack->setTrack($track);
        $playlistTrack->setPlaylist($playlist);
        $playlistTrack->setAddedAt($playlistTrack->getAddedAt());
        $playlistTrack->setAddedByUserId($playlistTrack->getAddedByUserId());
        $playlistTrack->setLocal($playlistTrack->isLocal());

        return $playlistTrack;
    }
}
