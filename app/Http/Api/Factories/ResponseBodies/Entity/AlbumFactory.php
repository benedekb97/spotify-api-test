<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\Album;

class AlbumFactory
{
    private ArtistFactory $artistFactory;

    private ExternalUrlFactory $externalUrlFactory;

    private ImageFactory $imageFactory;

    public function __construct(
        ArtistFactory $artistFactory,
        ExternalUrlFactory $externalUrlFactory,
        ImageFactory $imageFactory
    ) {
        $this->artistFactory = $artistFactory;
        $this->externalUrlFactory = $externalUrlFactory;
        $this->imageFactory = $imageFactory;
    }

    public function create(array $data): Album
    {
        $album = new Album();

        $album->setAlbumType($data['album_type']);

        foreach ($data['artists'] as $artist) {
            $album->addArtist(
                $this->artistFactory->create($artist)
            );
        }

        $album->setAvailableMarkets($data['available_markets']);
        $album->setExternalUrl(
            $this->externalUrlFactory->create($data['external_urls'])
        );
        $album->setHref($data['href']);
        $album->setId($data['id']);

        foreach ($data['images'] as $image) {
            $album->addImage(
                $this->imageFactory->create($image)
            );
        }

        $album->setName($data['name']);
        $album->setReleaseDate($data['release_date']);
        $album->setReleaseDatePrecision($data['release_date_precision']);
        $album->setTotalTracks($data['total_tracks'] ?? null);
        $album->setType($data['type']);
        $album->setUri($data['uri']);

        return $album;
    }
}
