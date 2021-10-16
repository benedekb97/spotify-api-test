<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Entities\Spotify\Playlist;
use App\Entities\Spotify\PlaylistInterface;
use App\Http\Api\Requests\GetPlaylistItemsRequest;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use App\Repositories\PlaylistRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Cache\Repository;

class PlaylistController extends Controller
{
    private SpotifyApiInterface $spotifyApi;

    private Repository $cache;

    public function __construct(
        SpotifyApi $spotifyApi,
        Repository $cache,
        EntityManager $entityManager
    ) {
        $this->spotifyApi = $spotifyApi;
        $this->cache = $cache;

        parent::__construct($entityManager);
    }

    public function show(string $playlist)
    {
        /** @var PlaylistInterface $playlist */
        $playlist = $this->findOr404(Playlist::class, $playlist);

        if (!$playlist->isViewableByUser($this->getUser())) {
            abort(403);
        }

        $playlist = $this->cache->remember(
            'playlist.' . $playlist->getId(),
            config('cache.ttl'),
            function () use ($playlist) {
                $this->spotifyApi->execute(new GetPlaylistItemsRequest($playlist->getId()));

                return $playlist;
            }
        );

        $this->entityManager->merge($playlist);

        return view(
            'pages.spotify.playlists.show',
            [
                'playlist' => $playlist
            ]
        );
    }
}
