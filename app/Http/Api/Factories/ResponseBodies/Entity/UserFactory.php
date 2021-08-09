<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\PublicUser;
use App\Http\Api\Responses\ResponseBodies\Entity\User;

class UserFactory
{
    private ExplicitContentSettingsFactory $explicitContentSettingsFactory;

    private ExternalUrlFactory $externalUrlFactory;

    private FollowersFactory $followersFactory;

    private ImageFactory $imageFactory;

    public function __construct(
        ExplicitContentSettingsFactory $explicitContentSettingsFactory,
        ExternalUrlFactory $externalUrlFactory,
        FollowersFactory $followersFactory,
        ImageFactory $imageFactory
    ) {
        $this->explicitContentSettingsFactory = $explicitContentSettingsFactory;
        $this->externalUrlFactory = $externalUrlFactory;
        $this->followersFactory = $followersFactory;
        $this->imageFactory = $imageFactory;
    }

    public function create(array $data): User
    {
        $user = new User();

        $user->setDisplayName($data['display_name']);

        $user->setExternalUrl(
            $this->externalUrlFactory->create($data['external_urls'])
        );

        $user->setFollowers(
            $this->followersFactory->create($data['followers'])
        );

        $user->setHref($data['href']);
        $user->setId($data['id']);

        foreach ($data['images'] as $image) {
            $user->addImage(
                $this->imageFactory->create($image)
            );
        }

        $user->setType($data['type']);
        $user->setUri($data['uri']);
        $user->setEmail($data['email']);

        return $user;
    }
}
