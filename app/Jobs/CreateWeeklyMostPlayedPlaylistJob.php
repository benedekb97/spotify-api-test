<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Entities\Spotify\PlaybackInterface;
use App\Entities\UserInterface;
use App\Factories\PlaylistFactoryInterface;
use App\Factories\TopTracksPlaylistCoverFactory;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Requests\AddTrackToPlaylistRequest;
use App\Http\Api\Requests\CreatePlaylistRequest;
use App\Http\Api\Requests\UploadPlaylistCoverRequest;
use App\Http\Api\Responses\ResponseBodies\CreatePlaylistResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Repositories\PlaybackRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class CreateWeeklyMostPlayedPlaylistJob
{
    use Dispatchable;

    private const MAX_SONG_COUNT = 25;

    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private SpotifyApiInterface $spotifyApi;

    private TopTracksPlaylistCoverFactory $coverFactory;

    private UserRepositoryInterface $userRepository;

    private PlaybackRepositoryInterface $playbackRepository;

    private PlaylistFactoryInterface $playlistFactory;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        SpotifyApi $spotifyApi,
        TopTracksPlaylistCoverFactory $coverFactory,
        UserRepositoryInterface $userRepository,
        PlaybackRepositoryInterface $playbackRepository,
        PlaylistFactoryInterface $playlistFactory
    ) {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->spotifyApi = $spotifyApi;
        $this->coverFactory = $coverFactory;
        $this->userRepository = $userRepository;
        $this->playbackRepository = $playbackRepository;
        $this->playlistFactory = $playlistFactory;
    }

    public function __invoke()
    {
        $users = $this->userRepository->findAllLoggedInWithSpotify();

        /** @var UserInterface $user */
        foreach ($users as $user) {
            Log::info('[WEEKLY_TOP_TRACKS] Creating weekly top tracks playlist for ' . $user->getName() . ' : ' . $user->getId());

            if (!$user->isLoggedInWithSpotify()) {
                $this->reauthenticateUser($user);
            }

            $playlistRequest = new CreatePlaylistRequest($user->getSpotifyId());
            $playlistRequest->setUser($user);

            $playlistResponse = $this->spotifyApi->execute(
                $playlistRequest,
                $this->getPlaylistName(),
                false,
                false,
                $this->getPlaylistDescription()
            );

            /** @var CreatePlaylistResponseBody $responseBody */
            $responseBody = $playlistResponse->getBody();

            $playlist = $this->playlistFactory->createFromSpotifyEntity($responseBody->getPlaylist());

            $tracks = [];

            $playbacks = $this->playbackRepository->getPlaybacksForUserBetween(
                $user,
                $this->getStartDate(),
                $this->getEndDate()
            );

            /** @var PlaybackInterface $playback */
            foreach ($playbacks as $playback) {
                if (array_key_exists($playback->getTrack()->getUri(), $tracks)) {
                    $tracks[$playback->getTrack()->getUri()]['count']++;
                } else {
                    $tracks[$playback->getTrack()->getUri()] = [
                        'track' => $playback->getTrack(),
                        'count' => 1,
                    ];
                }
            }

            uasort(
                $tracks,
                static function ($a, $b) {
                    return $b['count'] <=> $a['count'];
                }
            );

            $trackCount = min(count($tracks), self::MAX_SONG_COUNT);

            $tracks = array_slice($tracks, 0, $trackCount);

            $trackUris = array_map(
                static function ($track) {
                    return $track['track']->getUri();
                },
                $tracks
            );

            $this->spotifyApi->execute(
                (new AddTrackToPlaylistRequest($playlist->getId()))->setUser($user),
                $trackUris
            );

            // TODO: Test image upload for new albums

            $image = $this->coverFactory->create(
                $this->getStartDate(),
                $this->getEndDate()
            );

            $uploadImageRequest = new UploadPlaylistCoverRequest($playlist->getId());

            $uploadImageRequest->setHeaders(
                [
                    'Content-Type' => 'image/jpeg',
                ]
            );

            $uploadImageRequest->setUser($user);

            $base64Encoded = base64_encode($image->encode('jpeg')->encoded);

            $uploadImageRequest->setRequestBodyText($base64Encoded);

            $response = $this->spotifyApi->execute($uploadImageRequest);
        }
    }

    private function reauthenticateUser(UserInterface $user): void
    {
        $response = $this->spotifyAuthenticationApi->refreshAccessToken($user->getSpotifyRefreshToken());

        $user->setSpotifyAccessToken($response->getAccessToken());
        $user->setSpotifyAccessTokenExpiry(
            (new DateTime())->add(new DateInterval(sprintf('PT%sS', $response->getExpiresIn())))
        );

        $this->userRepository->add($user);
    }

    private function getPlaylistName(): string
    {
        return sprintf(
            'Weekly Top %d (%s)',
            self::MAX_SONG_COUNT,
            $this->getEndDate()->format('Y-m-d')
        );
    }

    private function getPlaylistDescription(): string
    {
        return sprintf(
            'Top played tracks between %s and %s',
            $this->getStartDate()->format('Y-m-d'),
            $this->getEndDate()->format('Y-m-d')
        );

    }

    private function getStartDate(): DateTimeInterface
    {
        return new DateTime('-1 week');
    }

    private function getEndDate(): DateTimeInterface
    {
        return new DateTime();
    }
}
