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

        if (array_key_exists('external_urls', $data)) {
            $artist->setExternalUrl(
                $this->externalUrlFactory->create($data['external_urls'])
            );
        }

        if (array_key_exists('followers', $data)) {
            $artist->setFollowers(
                $this->followersFactory->create($data['followers'])
            );
        }

        $artist->setGenres($data['genres'] ?? null);
        $artist->setHref($data['href'] ?? null);
        $artist->setId($data['id'] ?? null);

        if (array_key_exists('images', $data)) {
            foreach ($data['images'] as $image) {
                $artist->addImage(
                    $this->imageFactory->create($image)
                );
            }
        }

        $artist->setName($data['name'] ?? null);
        $artist->setPopularity($data['popularity'] ?? null);
        $artist->setType($data['type'] ?? null);
        $artist->setUri($data['uri'] ?? null);

        return $artist;
    }
}
