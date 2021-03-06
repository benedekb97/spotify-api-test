<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\AlbumInterface;
use App\Factories\AlbumFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Album;
use App\Http\Api\Responses\ResponseBodies\Entity\Copyright;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Repositories\AlbumRepositoryInterface;
use App\Services\Assigners\AlbumArtistAssigner;
use App\Services\Assigners\AlbumArtistAssignerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class AlbumProvider implements AlbumProviderInterface
{
    private AlbumRepositoryInterface $albumRepository;

    private AlbumFactoryInterface $albumFactory;

    private EntityManagerInterface $entityManager;

    private AlbumArtistAssignerInterface $albumArtistAssigner;

    public function __construct(
        AlbumRepositoryInterface $albumRepository,
        AlbumFactoryInterface $albumFactory,
        EntityManager $entityManager,
        AlbumArtistAssigner $albumArtistAssigner
    ) {
        $this->albumRepository = $albumRepository;
        $this->albumFactory = $albumFactory;
        $this->entityManager = $entityManager;
        $this->albumArtistAssigner = $albumArtistAssigner;
    }

    public function provide(Album $entity): AlbumInterface
    {
        $album = $this->albumRepository->find($entity->getId());

        if (!$album instanceof AlbumInterface) {
            /** @var AlbumInterface $album */
            $album = $this->albumFactory->createNew();

            $album->setId($entity->getId());
        }

        $album->setAvailableMarkets($entity->getAvailableMarkets());
        $album->setCopyrights($entity->getCopyrights()->map(fn(Copyright $c) => $c->toArray())->toArray());
        $album->setExternalids($entity->getExternalId() ? $entity->getExternalId()->toArray() : []);
        $album->setExternalUrls($entity->getExternalUrl() ? $entity->getExternalUrl()->toArray() : []);
        $album->setGenres($entity->getGenres());
        $album->setHref($entity->getHref());
        $album->setImages($entity->getImages()->map(fn(Image $i) => $i->toArray())->toArray());
        $album->setLabel($entity->getLabel());
        $album->setName($entity->getName());
        $album->setPopularity($entity->getPopularity());
        $album->setReleaseDate($entity->getReleaseDate());
        $album->setReleaseDatePrecision($entity->getReleaseDatePrecision());
        $album->setRestrictions($entity->getAlbumRestriction() ? $entity->getAlbumRestriction()->toArray() : []);
        $album->setTotalTracks($entity->getTotalTracks());
        $album->setType($entity->getType());
        $album->setUri($entity->getUri());

        $this->albumArtistAssigner->assign($album, $entity->getArtists());

        $this->entityManager->persist($album);
        $this->entityManager->flush();

        return $album;
    }

    public function provideForId(string $albumId): AlbumInterface
    {
        /** @var AlbumInterface|null $album */
        $album = $this->albumRepository->find($albumId);

        if (!$album instanceof AlbumInterface) {
            /** @var AlbumInterface $album */
            $album = $this->albumFactory->createNew();

            $album->setId($albumId);
        }


    }
}
