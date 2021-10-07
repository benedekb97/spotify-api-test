<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\ArtistInterface;
use App\Factories\ArtistFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Repositories\ArtistRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ArtistProvider implements ArtistProviderInterface
{
    private ArtistRepositoryInterface $artistRepository;

    private ArtistFactoryInterface $artistFactory;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ArtistRepositoryInterface $artistRepository,
        ArtistFactoryInterface $artistFactory,
        EntityManager $entityManager
    ) {
        $this->artistRepository = $artistRepository;
        $this->artistFactory = $artistFactory;
        $this->entityManager = $entityManager;
    }

    public function provide(Artist $entity): ArtistInterface
    {
        $artist = $this->artistRepository->find($entity->getId());

        if (!$artist instanceof ArtistInterface) {
            $artist = $this->artistFactory->createNew();

            $artist->setId($entity->getId());
        }

        $artist->setFollowers($entity->getFollowers() ? $entity->getFollowers()->toArray() : []);
        $artist->setGenres($entity->getGenres());
        $artist->setHref($entity->getHref());
        $artist->setImages($entity->getImages()->map(fn(Image $i) => $i->toArray())->toArray());
        $artist->setName($entity->getName());
        $artist->setPopularity($entity->getPopularity());
        $artist->setType($entity->getType());
        $artist->setUri($entity->getUri());

        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        return $artist;
    }
}
