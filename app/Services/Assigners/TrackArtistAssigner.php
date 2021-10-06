<?php

declare(strict_types=1);

namespace App\Services\Assigners;

use App\Entities\Spotify\ArtistInterface;
use App\Entities\Spotify\TrackInterface;
use App\Factories\ArtistFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist as ArtistEntity;
use App\Repositories\ArtistRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class TrackArtistAssigner implements TrackArtistAssignerInterface
{
    private ArtistRepositoryInterface $artistRepository;

    private ArtistFactoryInterface $artistFactory;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ArtistRepositoryInterface $artistRepository,
        ArtistFactoryInterface    $artistFactory,
        EntityManager             $entityManager
    )
    {
        $this->artistRepository = $artistRepository;
        $this->artistFactory = $artistFactory;
        $this->entityManager = $entityManager;
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
            $model = $this->getArtist($artist);

            $track->addArtist($model);
        }

        $this->entityManager->persist($track);
    }

    public function getArtist(ArtistEntity $entity): ArtistInterface
    {
        $artist = $this->artistRepository->find($entity->getId());

        if (!$artist instanceof ArtistInterface) {
            $artist = $this->artistFactory->createFromSpotifyEntity($entity);
        }

        return $artist;
    }
}
