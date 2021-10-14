<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Entities\Spotify\Album;
use App\Entities\Spotify\AlbumInterface;
use App\Http\Controllers\Controller;
use App\Services\Providers\AlbumStatisticsProvider;
use App\Services\Providers\AlbumStatisticsProviderInterface;
use Doctrine\ORM\EntityManager;

class AlbumController extends Controller
{
    private AlbumStatisticsProviderInterface $albumStatisticsProvider;

    public function __construct
    (
        EntityManager $entityManager,
        AlbumStatisticsProvider $albumStatisticsProvider
    )
    {
        $this->albumStatisticsProvider = $albumStatisticsProvider;

        parent::__construct($entityManager);
    }

    public function show(string $album)
    {
        /** @var AlbumInterface $album */
        $album = $this->findOr404(Album::class, $album);

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
