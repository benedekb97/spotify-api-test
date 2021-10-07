<?php

declare(strict_types=1);

namespace App\Services\Assigners;

use App\Entities\Spotify\AlbumInterface;
use App\Entities\Spotify\ArtistInterface;
use App\Factories\ArtistFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Repositories\ArtistRepositoryInterface;
use App\Services\Providers\Spotify\ArtistProvider;
use App\Services\Providers\Spotify\ArtistProviderInterface;
use Doctrine\Common\Collections\Collection;

class AlbumArtistAssigner implements AlbumArtistAssignerInterface
{
    private ArtistProviderInterface $artistProvider;

    public function __construct(
        ArtistProvider $artistProvider
    ) {
        $this->artistProvider = $artistProvider;
    }

    public function assign(AlbumInterface $album, Collection $artists): void
    {
        $artistIds = $artists->map(static fn (Artist $a) => $a->getId())->toArray();

        /** @var ArtistInterface $artist */
        foreach ($album->getArtists() as $artist) {
            if (!in_array($artist->getId(), $artistIds)) {
                $album->removeArtist($artist);
            }
        }

        /** @var Artist $artist */
        foreach ($artists as $artist) {
            $album->addArtist(
                $this->artistProvider->provide($artist)
            );
        }
    }
}
