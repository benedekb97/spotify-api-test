<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use LogicException;

class UpdatePlaylistTracksEvent
{
    use Dispatchable;

    private string $playlistId;

    private Collection $playlistTracks;

    public function __construct(
        string $playlistId,
        $playlistTracks
    ) {
        $this->playlistId = $playlistId;
        $this->playlistTracks = new Collection();

        if ($playlistTracks === null) {
            return;
        }

        if ($playlistTracks instanceof PlaylistTrack) {
            $this->$playlistTracks->add($playlistTracks);
        } elseif ($playlistTracks instanceof Collection) {
            if (($first = $playlistTracks->first()) === null || !$first instanceof PlaylistTrack) {
                return;
            }

            $this->playlistTracks = $playlistTracks;
        } else {
            throw new LogicException(
                sprintf(
                    'Unexpected type %s for \'playlists\' in %s',
                    get_class($playlistTracks),
                    get_class($this)
                )
            );
        }
    }

    public function getPlaylistId(): string
    {
        return $this->playlistId;
    }

    public function getPlaylistTracks(): Collection
    {
        return $this->playlistTracks;
    }
}
