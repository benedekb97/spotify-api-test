<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\ArtistInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;

class ArtistFactory extends EntityFactory implements ArtistFactoryInterface
{
    public function createFromSpotifyEntity(Artist $entity): ArtistInterface
    {
        /** @var ArtistInterface $artist */
        $artist = $this->createNew();

        $artist->setId($entity->getId());

        $artist->setFollowers($entity->getFollowers() ? $entity->getFollowers()->toArray() : []);
        $artist->setGenres($entity->getGenres());
        $artist->setHref($entity->getHref());
        $artist->setImages($entity->getImages()->map(fn(Image $i) => $i->toArray())->toArray());
        $artist->setName($entity->getName());
        $artist->setPopularity($entity->getPopularity());
        $artist->setType($entity->getType());
        $artist->setUri($entity->getUri());

        return $artist;
    }
}
