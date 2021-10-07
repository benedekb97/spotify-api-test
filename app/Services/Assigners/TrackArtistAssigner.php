<?php

declare(strict_types=1);

namespace App\Services\Assigners;

use App\Entities\Spotify\ArtistInterface;
use App\Entities\Spotify\TrackInterface;
use App\Factories\ArtistFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist as ArtistEntity;
use App\Repositories\ArtistRepositoryInterface;
use App\Services\Providers\Spotify\ArtistProvider;
use App\Services\Providers\Spotify\ArtistProviderInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class TrackArtistAssigner implements TrackArtistAssignerInterface
{
    private ArtistProviderInterface $artistProvider;

    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManager  $entityManager,
        ArtistProvider $artistProvider
    )
    {
        $this->entityManager = $entityManager;
        $this->artistProvider = $artistProvider;
    }

    public function assign(TrackInterface $track, Collection $artists): void
    {
        $artistIds = $artists->map(static fn(Artist $a) => $a->getId())->toArray();

        /** @var ArtistInterface $artist */
        foreach ($track->getArtists() as $artist) {
            if (!in_array($artist->getId(), $artistIds)) {
                $track->removeArtist($artist);
            }
        }

        /** @var Artist $artist */
        foreach ($artists as $artist) {
            $model = $this->artistProvider->provide($artist);

            $track->addArtist($model);
        }

        $this->entityManager->persist($track);
    }
}
