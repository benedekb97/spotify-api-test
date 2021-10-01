<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Entities\Spotify\PlaybackInterface;
use App\Entities\UserInterface;
use App\Events\HistoryUpdate;
use App\Factories\PlaybackFactoryInterface;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Requests\GetRecentlyPlayedTracksRequest;
use App\Http\Api\Responses\ResponseBodies\Entity\RecentlyPlayed;
use App\Http\Api\Responses\ResponseBodies\RecentlyPlayedTracksResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Repositories\PlaybackRepositoryInterface;
use App\Repositories\TrackRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Arr;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Log;

class GetRecentlyPlayedJob
{
    private const EPSILON = 5 * 60;

    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private SpotifyApiInterface $spotifyApi;

    private UserRepositoryInterface $userRepository;

    private EntityManager $entityManager;

    private PlaybackRepositoryInterface $playbackRepository;

    private PlaybackFactoryInterface $playbackFactory;

    private TrackRepositoryInterface $trackRepository;

    public function __construct(
        SpotifyAuthenticationApi    $spotifyAuthenticationApi,
        SpotifyApi                  $spotifyApi,
        UserRepositoryInterface     $userRepository,
        EntityManager               $entityManager,
        PlaybackRepositoryInterface $playbackRepository,
        PlaybackFactoryInterface    $playbackFactory,
        TrackRepositoryInterface    $trackRepository
    )
    {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->spotifyApi = $spotifyApi;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->playbackRepository = $playbackRepository;
        $this->playbackFactory = $playbackFactory;
        $this->trackRepository = $trackRepository;
    }

    public function __invoke()
    {
        $users = $this->userRepository->findAllLoggedInWithSpotify();

        $before = time();

        /** @var UserInterface $user */
        foreach ($users as $user) {
            if ($user->needsReauthentication()) {
                $response = $this->spotifyAuthenticationApi->refreshAccessToken($user->getSpotifyRefreshToken());

                if ($response === null) {
                    continue;
                }

                $tokenExpiry = (new DateTime())
                    ->add(new DateInterval(sprintf('PT%sS', $response->getExpiresIn())));

                $user->setSpotifyAccessToken($response->getAccessToken());
                $user->setSpotifyAccessTokenExpiry($tokenExpiry);

                $this->entityManager->persist($user);
            }

            if (count($this->playbackRepository->getRecentPlaybacksByUser($user)) < 50) {
                $limit = 50;
            } else {
                $limit = 5;
            }

            $request = new GetRecentlyPlayedTracksRequest($limit, null, $before);

            $request->setUser($user);

            Log::info('[RECENTLY_PLAYED] Getting recently played songs for user ' . $user->getId() . ' - ' . $user->getName());

            $recentlyPlayed = $this->spotifyApi->execute($request);

            if ($recentlyPlayed === null) {
                Log::error('[RECENTLY_PLAYED] No response. Skipping.');

                continue;
            }

            /** @var RecentlyPlayedTracksResponseBody|null $body */
            $body = $recentlyPlayed->getBody();

            if (!$body instanceof RecentlyPlayedTracksResponseBody) {
                Log::error('[RECENTLY_PLAYED] No body to response. Skipping.');

                continue;
            }

            Log::info('[RECENTLY_PLAYED] Processing ' . count($body->getItems()) . ' tracks for user ' . $user->getId() . ' - ' . $user->getName());

            /** @var RecentlyPlayed $recentlyPlayed */
            foreach ($body->getItems() as $recentlyPlayed) {
                if (
                    !$this->hasEntry(
                        $recentlyPlayed->getTrack()->getId(),
                        $user->getId(),
                        $recentlyPlayed->getPlayedAt()
                    )
                ) {
                    /** @var PlaybackInterface $playback */
                    $playback = $this->playbackFactory->createNew();

                    $playback->setUser($user);

                    $playback->setTrack(
                        $this->trackRepository->find($recentlyPlayed->getTrack()->getId())
                    );

                    $playback->setPlayedAt($recentlyPlayed->getPlayedAt());

                    $this->entityManager->persist($playback);
                }
            }

            $this->entityManager->flush();

            $playbacks = array_slice($this->playbackRepository->getRecentPlaybacksByUser($user), 0, 20);

            event(new HistoryUpdate($user, collect($playbacks)));
        }
    }

    private function hasEntry(string $trackId, int $userId, DateTimeInterface $playedAt): bool
    {
        $start = $playedAt->sub(new DateInterval(sprintf('PT%dS', self::EPSILON)));
        $end = $playedAt->add(new DateInterval(sprintf('PT%dS', self::EPSILON)));

        $track = $this->trackRepository->find($trackId);
        $user = $this->userRepository->find($userId);

        $playback = Arr::first(
            $this->playbackRepository->getPlaybacksForUserAndTrackBetween(
                $user,
                $track,
                $start,
                $end
            )
        );

        return $playback !== null;
    }
}
