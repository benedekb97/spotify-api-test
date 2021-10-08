<?php

declare(strict_types=1);

namespace App\Services\Synchronizers;

use App\Entities\Spotify\UserTrackInterface;
use App\Entities\UserInterface;
use App\Factories\UserTrackFactoryInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\SavedTrack;
use App\Repositories\TrackRepositoryInterface;
use App\Repositories\UserTrackRepositoryInterface;
use App\Services\Providers\Spotify\TrackProvider;
use App\Services\Providers\Spotify\TrackProviderInterface;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Collection;

class UserTracksSynchronizer implements UserTracksSynchronizerInterface
{
    private EntityManagerInterface $entityManager;

    private UserTrackRepositoryInterface $userTrackRepository;

    private TrackProviderInterface $trackProvider;

    private UserTrackFactoryInterface $userTrackFactory;

    public function __construct(
        EntityManager $entityManager,
        UserTrackRepositoryInterface $userTrackRepository,
        TrackProvider $trackProvider,
        UserTrackFactoryInterface $userTrackFactory
    ) {
        $this->entityManager = $entityManager;
        $this->userTrackRepository = $userTrackRepository;
        $this->trackProvider = $trackProvider;
        $this->userTrackFactory = $userTrackFactory;
    }

    public function synchronize(UserInterface $user, array $savedTracks): void
    {
        /** @var UserTrackInterface $userTrack */
        foreach ($user->getUserTracks() as $userTrack) {
            if (($savedTracks[$userTrack->getTrack()->getId()] ?? null) === null) {
                $user->removeUserTrack($userTrack);
                $userTrack->setTrack(null);

                $this->entityManager->remove($userTrack);
            } elseif (
                array_key_exists($userTrack->getTrack()->getId(), $savedTracks) &&
                $savedTracks[$userTrack->getTrack()->getId()] !== $userTrack->getAddedAt()
            ) {
                $userTrack->setAddedAt($savedTracks[$userTrack->getTrack()->getId()]);

                $this->entityManager->persist($userTrack);
            }

            $this->entityManager->flush();
        }

        /** @var DateTimeInterface $addedAt */
        foreach ($savedTracks as $trackId => $addedAt) {
            $track = $this->trackProvider->provideForId($trackId);

            $userTrack = $this->userTrackRepository->findOneByTrackUserAndAddedAt($track, $user, $addedAt);

            if (!$userTrack instanceof UserTrackInterface) {
                $userTrack = $this->userTrackFactory->createForUserAndTrack($user, $track);
            }

            $userTrack->setAddedAt($addedAt);

            $this->entityManager->persist($userTrack);
        }

        $this->entityManager->flush();
    }
}
