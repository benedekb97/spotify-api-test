<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\TrackInterface;
use App\Factories\TrackFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use App\Repositories\TrackRepositoryInterface;

class TrackProvider implements TrackProviderInterface
{
    private TrackRepositoryInterface $trackRepository;

    private TrackFactoryInterface $trackFactory;

    public function __construct(
        TrackRepositoryInterface $trackRepository,
        TrackFactoryInterface $trackFactory
    ) {
        $this->trackRepository = $trackRepository;
        $this->trackFactory = $trackFactory;
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

        return $track;
    }
}
