<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\TrackInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Track;

class TrackFactory extends EntityFactory implements TrackFactoryInterface
{
    public function __construct(string $className)
    {
        parent::__construct($className);
    }

    public function createFromSpotifyEntity(Track $track): TrackInterface
    {
        /** @var TrackInterface $track */
        $model = $this->createNew();

        $model->setPopularity($track->getPopularity());
        $model->setExplicit($track->getExplicit());
        $model->setName($track->getName());
        $model->setType($track->getType());
        $model->setAvailableMarkets($track->getAvailableMarkets());
        $model->setDiscNumber($track->getDiscNumber());
        $model->setDurationms($track->getDurationMs());
        $model->setExternalids($track->getExternalId() ? $track->getExternalId()->toArray() : []);
        $model->setExternalUrls($track->getExternalUrl() ? $track->getExternalUrl()->toArray() : []);
        $model->setHref($track->getHref());
        $model->setLocal($track->getIsLocal());
        $model->setPlayable($track->getIsPlayable());
        $model->setPreviewUrl($track->getPreviewUrl());
        $model->setTrackNumber($track->getTrackNumber());
        $model->setUri($track->getUri());

        return $track;
    }
}
