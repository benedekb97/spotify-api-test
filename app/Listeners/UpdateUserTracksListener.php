<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Spotify\TrackInterface;
use App\Http\Api\Events\UpdateUserTracksEvent;
use App\Http\Api\Responses\ResponseBodies\Entity\SavedTrack;
use App\Repositories\TrackRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserTrackRepositoryInterface;
use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class UpdateUserTracksListener
{
    private LoggerInterface $logger;

    private UserTrackRepositoryInterface $userTrackRepository;

    private TrackRepositoryInterface $trackRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        LogManager $logManager,
        UserTrackRepositoryInterface $userTrackRepository,
        TrackRepositoryInterface $trackRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->logger = $logManager->channel(config('logging.default'));
        $this->userTrackRepository = $userTrackRepository;
        $this->trackRepository = $trackRepository;
        $this->userRepository = $userRepository;
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
            $track = $this->trackRepository->find($savedTrack->getTrack()->getId());


        }
    }

    private function getTrack(string $id): TrackInterface
    {

    }
}
