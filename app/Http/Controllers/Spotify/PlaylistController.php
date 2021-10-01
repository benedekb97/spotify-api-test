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

    private PlaylistRepositoryInterface $playlistRepository;

    public function __construct(
        SpotifyApi $spotifyApi,
        Repository $cache,
        EntityManager $entityManager,
        PlaylistRepositoryInterface $playlistRepository
    ) {
        $this->spotifyApi = $spotifyApi;
        $this->cache = $cache;
        $this->playlistRepository = $playlistRepository;

        parent::__construct($entityManager);
    }

    public function show(string $playlist)
    {
        /** @var PlaylistInterface $playlist */
        $playlist = $this->playlistRepository->find($playlist);

        if ($playlist === null) {
            abort(404);
        }

        if (!$playlist->isCollaborative() && (!$playlist->hasLocalUser() || $playlist->getLocalUser() !== $this->getUser())) {
            abort(401);
        }

        $playlist = $this->cache->remember(
            'playlist.' . $playlist->getId(),
            config('cache.ttl'),
            function () use ($playlist) {
                $this->spotifyApi->execute(new GetPlaylistItemsRequest($playlist->getId()));

                return $this->playlistRepository->find($playlist->getId());
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
