<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Spotify\PlaybackInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;
use DateTimeInterface;
use Doctrine\Persistence\ObjectRepository;

interface PlaybackRepositoryInterface extends ObjectRepository
{
    public function getPlaybacksByTrackGroupedByDate(TrackInterface $track, UserInterface $user): array;

    public function getRecentPlaybacksByUser(UserInterface $user): array;

    /**
     * @param UserInterface $user
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     *
     * @return array<PlaybackInterface>
     */
    public function getPlaybacksForUserBetween(
        UserInterface $user,
        DateTimeInterface $start,
        DateTimeInterface $end
    ): array;

    /**
     * @param UserInterface     $user
     * @param TrackInterface    $track
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     *
     * @return array<PlaybackInterface>
     */
    public function getPlaybacksForUserAndTrackBetween(
        UserInterface $user,
        TrackInterface $track,
        DateTimeInterface $start,
        DateTimeInterface $end
    ): array;
}
