<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\Track;

class TrackFactory
{
    private AlbumFactory $albumFactory;

    private ArtistFactory $artistFactory;

    private ExternalIdFactory $externalIdFactory;

    private ExternalUrlFactory $externalUrlFactory;

    public function __construct(
        AlbumFactory $albumFactory,
        ArtistFactory $artistFactory,
        ExternalIdFactory $externalIdFactory,
        ExternalUrlFactory $externalUrlFactory
    ) {
        $this->albumFactory = $albumFactory;
        $this->artistFactory = $artistFactory;
        $this->externalIdFactory = $externalIdFactory;
        $this->externalUrlFactory = $externalUrlFactory;
    }

    public function create(array $data): Track
    {
        $track = new Track();

        $track->setAlbum(
            $this->albumFactory->create($data['album'])
        );

        foreach ($data['artists'] as $artist) {
            $track->addArtist(
                $this->artistFactory->create($artist)
            );
        }

        $track->setAvailableMarkets($data['available_markets']);
        $track->setDiscNumber($data['disc_number']);
        $track->setDurationMs($data['duration_ms']);
        $track->setExplicit($data['explicit']);

        $track->setExternalId(
            $this->externalIdFactory->create($data['external_ids'])
        );

        $track->setExternalUrl(
            $this->externalUrlFactory->create($data['external_urls'])
        );

        $track->setHref($data['href']);
        $track->setId($data['id']);
        $track->setIsLocal($data['is_local']);
        $track->setName($data['name']);
        $track->setPopularity($data['popularity']);
        $track->setPreviewUrl($data['preview_url']);
        $track->setTrackNumber($data['track_number']);
        $track->setType($data['type']);
        $track->setUri($data['uri']);

        return $track;
    }
}
