<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Spotify\TrackInterface;
use App\Entities\Spotify\UserTrackInterface;
use App\Entities\UserInterface;
use App\Factories\UserTrackFactoryInterface;
use App\Http\Api\Events\UpdateUserTracksEvent;
use App\Http\Api\Responses\ResponseBodies\Entity\SavedTrack;
use App\Repositories\TrackRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserTrackRepositoryInterface;
use App\Services\Providers\Spotify\TrackProvider;
use App\Services\Providers\Spotify\TrackProviderInterface;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class UpdateUserTracksListener
{
    private LoggerInterface $logger;

    private TrackProviderInterface $trackProvider;

    private UserTrackRepositoryInterface $userTrackRepository;

    private UserTrackFactoryInterface $userTrackFactory;

    private EntityManagerInterface $entityManager;

    public function __construct(
        LogManager $logManager,
        TrackProvider $trackProvider,
        UserTrackRepositoryInterface $userTrackRepository,
        UserTrackFactoryInterface $userTrackFactory,
        EntityManager $entityManager
    ) {
        $this->logger = $logManager->channel(config('logging.default'));
        $this->trackProvider = $trackProvider;
        $this->userTrackRepository = $userTrackRepository;
        $this->userTrackFactory = $userTrackFactory;
        $this->entityManager = $entityManager;
    }

    public function handle(UpdateUserTracksEvent $event): void
    {
        if (!$class = $event->getTracks()->first() instanceof SavedTrack) {
            $this->logger->log(
                LogLevel::ERROR,
                sprintf(
                    'Invalid type passed to %s: expecting SavedTrack received %s',
                    get_class($this),
                    is_object($class) ? get_class($class) : get_debug_type($class)
                )
            );

            return;
        }

        /** @var SavedTrack $savedTrack */
        foreach ($event->getTracks() as $savedTrack) {
            $userTrack = $this->getUserTrack(
                $this->trackProvider->provide($savedTrack->getTrack()),
                $savedTrack->getAddedAt(),
                $event->getUser()
            );

            $this->entityManager->persist($userTrack);
        }

        $this->entityManager->flush();
    }

    private function getUserTrack(
        TrackInterface $track,
        DateTimeInterface $addedAt,
        UserInterface $user
    ): UserTrackInterface
    {
        $userTrack = $this->userTrackRepository->findOneByTrackUserAndAddedAt($track, $user, $addedAt);

        if (!$userTrack instanceof UserTrackInterface) {
            $userTrack = $this->userTrackFactory->createForUserAndTrack($user, $track);
        }

        $userTrack->setAddedAt($addedAt);

        return $userTrack;
    }
}
