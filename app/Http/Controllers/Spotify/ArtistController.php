<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Entities\Spotify\Artist;
use App\Entities\Spotify\ArtistInterface;
use App\Http\Controllers\Controller;
use App\Services\Providers\ArtistTracksProvider;
use App\Services\Providers\ArtistTracksProviderInterface;
use App\Services\Providers\Spotify\ArtistProvider;
use App\Services\Providers\Spotify\ArtistProviderInterface;
use Doctrine\ORM\EntityManager;

class ArtistController extends Controller
{
    private ArtistProviderInterface $artistProvider;

    private ArtistTracksProviderInterface $artistTracksProvider;

    public function __construct(
        EntityManager $entityManager,
        ArtistProvider $artistProvider,
        ArtistTracksProvider $artistTracksProvider
    )
    {
        $this->artistProvider = $artistProvider;
        $this->artistTracksProvider = $artistTracksProvider;

        parent::__construct($entityManager);
    }

    public function show(string $artist)
    {
        /** @var ArtistInterface $artist */
        $artist = $this->findOr404(Artist::class, $artist);

        $tracks = $this->artistTracksProvider->provide($artist);

        return view(
            'pages.spotify.artists.show',
            [
                'artist' => $artist,
                'tracks' => $tracks,
                'user' => $this->getUser(),
            ]
        );
    }
}
