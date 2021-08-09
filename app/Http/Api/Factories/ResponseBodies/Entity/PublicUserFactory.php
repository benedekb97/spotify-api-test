<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\PublicUser;
use function Symfony\Component\String\b;

class PublicUserFactory
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

    public function create(array $data): PublicUser
    {
        $user = new PublicUser();

        $user->setDisplayName($data['display_name']);

        $user->setExternalUrl(
            $this->externalUrlFactory->create($data['external_urls'])
        );

        if (isset($data['followers'])) {
            $user->setFollowers(
                $this->followersFactory->create($data['followers'])
            );
        }

        $user->setHref($data['href']);
        $user->setId($data['id']);

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $user->addImage(
                    $this->imageFactory->create($image)
                );
            }
        }

        return $user;
    }
}
