<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use App\Http\Api\Responses\ResponseBodies\Entity\Playlist;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use LogicException;

class UpdatePlaylistsEvent
{
    private User $user;

    private Collection $playlists;

    use Dispatchable;

    public function __construct(User $user, $playlists)
    {
        $this->user = $user;
        $this->playlists = new Collection();

        if ($playlists === null) {
            return;
        }

        if ($playlists instanceof Playlist) {
            $this->playlists->add($playlists);
        } elseif ($playlists instanceof Collection) {
            if (($first = $playlists->first()) === null || !$first instanceof Playlist) {
                return;
            }

            $this->playlists = $playlists;
        } else {
            throw new LogicException(
                sprintf(
                    'Unexpected type %s for \'playlists\' in %s',
                    get_class($playlists),
                    get_class($this)
                )
            );
        }
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }
}
