<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Entities\Spotify\Album;
use App\Entities\Spotify\AlbumInterface;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use App\Services\Providers\AlbumStatisticsProvider;
use App\Services\Providers\AlbumStatisticsProviderInterface;
use App\Services\Synchronizers\AlbumSynchronizer;
use App\Services\Synchronizers\AlbumSynchronizerInterface;
use Doctrine\ORM\EntityManager;

class AlbumController extends Controller
{
    private AlbumStatisticsProviderInterface $albumStatisticsProvider;

    private AlbumSynchronizerInterface $albumSynchronizer;

    public function __construct
    (
        EntityManager $entityManager,
        AlbumStatisticsProvider $albumStatisticsProvider,
        AlbumSynchronizer $albumSynchronizer
    )
    {
        $this->albumStatisticsProvider = $albumStatisticsProvider;
        $this->albumSynchronizer = $albumSynchronizer;

        parent::__construct($entityManager);
    }

    public function show(string $album)
    {
        /** @var AlbumInterface $album */
        $album = $this->findOr404(Album::class, $album);

        if ($album->getTotalTracks() !== $album->getTracks()->count()) {
            $album = $this->albumSynchronizer->synchronize($album);
        }

        $statistics = $this->albumStatisticsProvider->provideForUser($album, $this->getUser());

        return view(
            'pages.spotify.albums.show',
            [
                'album' => $album,
                'statistics' => $statistics,
            ]
        );
    }
}
