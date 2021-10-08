<?php

declare(strict_types=1);

namespace App\Services\Synchronizers;

use App\Entities\Spotify\UserTrackInterface;
use App\Entities\UserInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\SavedTrack;
use App\Repositories\TrackRepositoryInterface;
use App\Repositories\UserTrackRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Collection;

class UserTracksSynchronizer implements UserTracksSynchronizerInterface
{
    private EntityManagerInterface $entityManager;

    private UserTrackRepositoryInterface $userTrackRepository;

    private TrackRepositoryInterface $trackRepository;

    public function __construct(
        EntityManager $entityManager,
        UserTrackRepositoryInterface $userTrackRepository,
        TrackRepositoryInterface $trackRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userTrackRepository = $userTrackRepository;
        $this->trackRepository = $trackRepository;
    }

    public function synchronize(UserInterface $user, Collection $savedTracks): void
    {
        $savedTracks = $savedTracks->mapWithKeys(
            static function (SavedTrack $savedTrack) {
                return [$savedTrack->getTrack()->getId() => $savedTrack->getAddedAt()];
            }
        )->toArray();

        dd($savedTracks);

        /** @var UserTrackInterface $userTrack */
        foreach ($user->getUserTracks() as $userTrack) {
            if (!in_array($userTrack->getTrack()->getId(), $savedTrackIds, true)) {
                $user->removeUserTrack($userTrack);
                $userTrack->setTrack(null);

                $this->entityManager->remove($userTrack);
            }
        }
    }
}
