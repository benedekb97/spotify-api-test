<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\PlaylistInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Http\Api\Responses\ResponseBodies\Entity\Playlist;

class PlaylistFactory extends EntityFactory implements PlaylistFactoryInterface
{
    public function createFromSpotifyEntity(Playlist $entity): PlaylistInterface
    {
        /** @var PlaylistInterface $playlist */
        $playlist = $this->createNew();

        $playlist->setId($entity->getId());
        $playlist->setCollaborative($entity->isCollaborative());
        $playlist->setDescription($entity->getDescription());
        $playlist->setExternalUrl($entity->getExternalUrl()->toArray());
        $playlist->setFollowers(
            $entity->getFollowers() !== null ? $entity->getFollowers()->toArray() : []
        );
        $playlist->setHref($entity->getHref());
        $playlist->setImages(
            $entity->getImages() !== null ? $entity->getImages()->map(
                fn (Image $i) => $i->toArray()
            )->toArray() : []
        );
        $playlist->setName($entity->getName());
        $playlist->setOwnerUserid($entity->getOwner()->getId());
        $playlist->setSnapshotId($entity->getSnapshotId());
        $playlist->setType($entity->getType());
        $playlist->setUri($entity->getUri());

        return $playlist;
    }
}
