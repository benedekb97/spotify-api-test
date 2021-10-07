<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\TrackInterface;
use App\Factories\TrackFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Album;
use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use App\Repositories\TrackRepositoryInterface;
use App\Services\Assigners\TrackArtistAssigner;
use App\Services\Assigners\TrackArtistAssignerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class TrackProvider implements TrackProviderInterface
{
    private TrackRepositoryInterface $trackRepository;

    private TrackFactoryInterface $trackFactory;

    private TrackArtistAssignerInterface $trackArtistAssigner;

    private AlbumProviderInterface $albumProvider;

    private EntityManagerInterface $entityManager;

    public function __construct(
        TrackRepositoryInterface $trackRepository,
        TrackFactoryInterface $trackFactory,
        TrackArtistAssigner $trackArtistAssigner,
        AlbumProvider $albumProvider,
        EntityManager $entityManager
    ) {
        $this->trackRepository = $trackRepository;
        $this->trackFactory = $trackFactory;
        $this->trackArtistAssigner = $trackArtistAssigner;
        $this->albumProvider = $albumProvider;
        $this->entityManager = $entityManager;
    }

    public function provide(Track $entity): TrackInterface
    {
        $track = $this->trackRepository->find($entity->getId());

        if (!$track instanceof TrackInterface) {
            /** @var TrackInterface $track */
            $track = $this->trackFactory->createNew();
        }

        $track->setId($entity->getId());
        $track->setPopularity($entity->getPopularity());
        $track->setExplicit($entity->getExplicit());
        $track->setName($entity->getName());
        $track->setType($entity->getType());
        $track->setAvailableMarkets($entity->getAvailableMarkets());
        $track->setDiscNumber($entity->getDiscNumber());
        $track->setDurationms($entity->getDurationMs());
        $track->setExternalids($entity->getExternalId() ? $entity->getExternalId()->toArray() : []);
        $track->setExternalUrls($entity->getExternalUrl() ? $entity->getExternalUrl()->toArray() : []);
        $track->setHref($entity->getHref());
        $track->setLocal($entity->getIsLocal());
        $track->setPlayable($entity->getIsPlayable());
        $track->setPreviewUrl($entity->getPreviewUrl());
        $track->setTrackNumber($entity->getTrackNumber());
        $track->setUri($entity->getUri());

        $this->trackArtistAssigner->assign($track, $entity->getArtists());

        if ($entity->getAlbum() instanceof Album) {
            $track->setAlbum(
                $this->albumProvider->provide($entity->getAlbum())
            );
        }

        $this->entityManager->persist($track);
        $this->entityManager->flush();

        return $track;
    }
}
