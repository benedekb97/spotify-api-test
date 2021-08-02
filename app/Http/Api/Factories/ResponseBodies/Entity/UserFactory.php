<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

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

        $user->setCountry($data['country']);
        $user->setDisplayName($data['display_name']);
        $user->setEmail($data['email']);

        $user->setExplicitContent(
            $this->explicitContentSettingsFactory->create($data['explicit_content'])
        );

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

        $user->setProduct($data['product']);
        $user->setType($data['type']);
        $user->setUri($data['uri']);

        return $user;
    }
}
