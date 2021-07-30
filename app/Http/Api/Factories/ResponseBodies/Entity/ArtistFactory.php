<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\Artist;

class ArtistFactory
{
    private ExternalUrlFactory $externalUrlFactory;

    private FollowersFactory $followersFactory;

    private ImageFactory $imageFactory;

    public function __construct(
        ExternalUrlFactory $externalUrlFactory,
        FollowersFactory $followersFactory,
        ImageFactory $imageFactory
    ) {
        $this->externalUrlFactory = $externalUrlFactory;
        $this->followersFactory = $followersFactory;
        $this->imageFactory = $imageFactory;
    }

    public function create(array $data): Artist
    {
        $artist = new Artist();

        $artist->setExternalUrl(
            $this->externalUrlFactory->create($data['external_urls'])
        );

        $artist->setFollowers(
            $this->followersFactory->create($data['followers'])
        );

        $artist->setGenres($data['genres'] ?? []);
        $artist->setHref($data['href']);
        $artist->setId($data['id']);

        foreach ($data['images'] as $image) {
            $artist->addImage(
                $this->imageFactory->create($image)
            );
        }

        $artist->setName($data['name']);
        $artist->setPopularity($data['popularity']);
        $artist->setType($data['type']);
        $artist->setUri($data['uri']);

        return $artist;
    }
}
