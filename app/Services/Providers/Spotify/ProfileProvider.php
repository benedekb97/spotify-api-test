<?php

declare(strict_types=1);

namespace App\Services\Providers\Spotify;

use App\Entities\Spotify\ProfileInterface;
use App\Factories\ProfileFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Http\Api\Responses\ResponseBodies\Entity\User;
use App\Repositories\ProfileRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ProfileProvider implements ProfileProviderInterface
{
    private ProfileRepositoryInterface $profileRepository;

    private ProfileFactoryInterface $profileFactory;

    private UserRepositoryInterface $userRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ProfileRepositoryInterface $profileRepository,
        ProfileFactoryInterface $profileFactory,
        UserRepositoryInterface $userRepository,
        EntityManager $entityManager
    ) {
        $this->profileRepository = $profileRepository;
        $this->profileFactory = $profileFactory;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function provide(User $user): ProfileInterface
    {
        /** @var ProfileInterface|null $profile */
        $profile = $this->profileRepository->find($user->getId());

        if (!$profile instanceof ProfileInterface) {
            /** @var ProfileInterface $profile */
            $profile = $this->profileFactory->createNew();

            $profile->setId($user->getId());
        }

        $profile->setExplicitContent($user->getExplicitContent()->toArray());
        $profile->setFollowers($user->getFollowers()->toArray());
        $profile->setExternalUrl($user->getExternalUrl()->toArray());
        $profile->setCountry($user->getCountry());
        $profile->setDisplayName($user->getDisplayName());
        $profile->setHref($user->getHref());
        $profile->setUri($user->getUri());
        $profile->setImages($user->getImages()->map(fn (Image $i) => $i->toArray())->toArray());
        $profile->setType($user->getType());
        $profile->setProduct($user->getProduct());

        $profile->setUser($this->userRepository->findOneBySpotifyId($user->getId()));

        $this->entityManager->persist($profile);
        $this->entityManager->flush();

        return $profile;
    }
}
