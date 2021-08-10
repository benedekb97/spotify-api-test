<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Http\Api\Events\UpdatePlaylistsEvent;
use App\Http\Api\Requests\GetPlaylistItemsRequest;
use App\Http\Api\Responses\ResponseBodies\Entity\Playlist as PlaylistEntity;
use App\Http\Api\Responses\ResponseBodies\GetPlaylistItemsResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Models\Spotify\Playlist;
use Illuminate\Support\Facades\Log;

class UpdatePlaylistsListener
{
    private SpotifyApiInterface $spotifyApi;

    public function __construct(
        SpotifyApi $spotifyApi
    ) {
        $this->spotifyApi = $spotifyApi;
    }

    public function handle(UpdatePlaylistsEvent $event): void
    {
        $playlists = $event->getPlaylists();
        $user = $event->getUser();

        /** @var PlaylistEntity $playlist */
        foreach ($playlists as $playlist) {
            $snapshotId = Playlist::find($playlist->getId())->snapshot_id ?? null;

            Playlist::createFromEntity($playlist);

            if ($snapshotId === $playlist->getSnapshotId()) {
                Log::info('Snapshot ID matches for playlist ' . $playlist->getId() . '. Skipping...');

                continue;
            }

            if (
                ($playlist->getTracksData() !== null && array_key_exists('href', $playlist->getTracksData()))
                ||
                ($playlist->getTracks()->isEmpty() && empty($playlist->getTracksData()))
            ) {
                $offset = 0;

                do {
                    if (isset($responseBody)) {
                        $offset += $responseBody->getLimit();
                    }

                    $response = $this->spotifyApi->execute(
                        (new GetPlaylistItemsRequest($playlist->getId(), $offset))->setUser($user)
                    );

                    /** @var GetPlaylistItemsResponseBody $responseBody */
                    $responseBody = $response->getBody();
                } while ($responseBody->getLimit() + $offset < $responseBody->getTotal());
            }
        }
    }
}
