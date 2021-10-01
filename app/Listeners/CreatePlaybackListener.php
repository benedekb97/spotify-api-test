<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Spotify\PlaybackInterface;
use App\Entities\UserInterface;
use App\Factories\PlaybackFactoryInterface;
use App\Http\Api\Events\CreatePlaybackEvent;
use App\Repositories\PlaybackRepositoryInterface;
use App\Repositories\TrackRepositoryInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CreatePlaybackListener
{
    private const DURATION_OFFSET = 60 * 2;

    private PlaybackFactoryInterface $playbackFactory;

    private PlaybackRepositoryInterface $playbackRepository;

    private TrackRepositoryInterface $trackRepository;

    public function __construct(
        PlaybackFactoryInterface    $playbackFactory,
        PlaybackRepositoryInterface $playbackRepository,
        TrackRepositoryInterface    $trackRepository
    )
    {
        $this->playbackFactory = $playbackFactory;
        $this->playbackRepository = $playbackRepository;
        $this->trackRepository = $trackRepository;
    }

    public function handle(CreatePlaybackEvent $event): void
    {
        $track = $event->getTrack();

        if ($track === null) {
            return;
        }

        if (!$this->hasRecentPlayback($track->getId(), (int)($track->getDurationMs() / 1000) + self::DURATION_OFFSET)) {
            /** @var PlaybackInterface $playback */
            $playback = $this->playbackFactory->createNew();

            $playback->setUser($this->getUser());
            $playback->setTrack(
                $this->trackRepository->find($track->getId())
            );
            $playback->setPlayedAtNow();

            $this->playbackRepository->add($playback);
        }
    }

    private function hasRecentPlayback(string $trackId, int $duration): bool
    {
        $track = $this->trackRepository->find($trackId);

        $playback = Arr::first(
            $this->playbackRepository->getPlaybacksForUserAndTrackBetween(
                $this->getUser(),
                $track,
                (new DateTime())->sub(new DateInterval("PT{$duration}S")),
                (new DateTime())->add(new DateInterval("PT{$duration}S"))
            )
        );

        return $playback !== null;
    }

    private function getUser(): ?UserInterface
    {
        $user = Auth::user();

        if ($user instanceof UserInterface) {
            return $user;
        }

        return null;
    }
}
