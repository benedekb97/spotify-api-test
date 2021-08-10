<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Http\Api\Requests\GetPlaylistItemsRequest;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use App\Models\Spotify\Playlist;
use Illuminate\Cache\Repository;

class PlaylistController extends Controller
{
    private SpotifyApiInterface $spotifyApi;

    private Repository $cache;

    public function __construct(
        SpotifyApi $spotifyApi,
        Repository $cache
    ) {
        $this->spotifyApi = $spotifyApi;
        $this->cache = $cache;
    }

    public function show(Playlist $playlist)
    {
        $playlist = $this->cache->remember(
            'playlist.' . $playlist->id,
            config('cache.ttl'),
            function () use ($playlist) {
                $this->spotifyApi->execute(new GetPlaylistItemsRequest($playlist->id));

                return Playlist::find($playlist->id);
            }
        );

        return view(
            'pages.spotify.playlists.show',
            [
                'playlist' => $playlist
            ]
        );
    }
}
