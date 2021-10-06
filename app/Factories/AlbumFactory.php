<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\AlbumInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Album as AlbumEntity;
use App\Http\Api\Responses\ResponseBodies\Entity\Copyright;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;

class AlbumFactory extends EntityFactory implements AlbumFactoryInterface
{
    public function createFromSpotifyEntity(AlbumEntity $entity): AlbumInterface
    {
        /** @var AlbumInterface $album */
        $album = $this->createNew();

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

        return $album;
    }
}
