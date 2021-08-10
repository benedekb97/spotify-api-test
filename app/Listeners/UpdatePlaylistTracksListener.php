<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Http\Api\Events\UpdatePlaylistTracksEvent;
use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack as PlaylistTrackEntity;
use App\Models\Spotify\PlaylistTrack;

class UpdatePlaylistTracksListener
{
    public function handle(UpdatePlaylistTracksEvent $event): void
    {
        $playlistId = $event->getPlaylistId();
        $playlistTracks = $event->getPlaylistTracks();

        /** @var PlaylistTrackEntity $playlistTrack */
        foreach ($playlistTracks as $playlistTrack) {
            PlaylistTrack::createFromEntity($playlistTrack, $playlistId);
        }
    }
}
