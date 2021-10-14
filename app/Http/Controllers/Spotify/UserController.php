<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\UserTrackInterface;
use App\Entities\UserInterface;
use App\Http\Api\Requests\AddItemToQueueRequest;
use App\Http\Api\Requests\CurrentlyPlayingRequest;
use App\Http\Api\Requests\GetProfileRequest;
use App\Http\Api\Requests\GetRecentlyPlayedTracksRequest;
use App\Http\Api\Requests\GetRecommendationsRequest;
use App\Http\Api\Requests\TopArtistsRequest;
use App\Http\Api\Requests\TopTracksRequest;
use App\Http\Api\Responses\ResponseBodies\Entity\RecentlyPlayed;
use App\Http\Api\Responses\ResponseBodies\Entity\User;
use App\Http\Api\Responses\SpotifyResponseInterface;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserTrackRepositoryInterface;
use App\Util\CacheTags;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private const ENDPOINT_USER_PROFILE = 'v1/me';

    private const TYPE_TRACKS = 'tracks';
    private const TYPE_ARTISTS = 'artists';

    private const TYPE_MAP = [
        'Tracks' => self::TYPE_TRACKS,
        'Artists' => self::TYPE_ARTISTS,
    ];

    private Repository $cache;

    private SpotifyApiInterface $spotifyApi;

    private UserTrackRepositoryInterface $userTrackRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        Repository $cache,
        SpotifyApi $spotifyApi,
        EntityManager $entityManager,
        UserTrackRepositoryInterface $userTrackRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->cache = $cache;
        $this->spotifyApi = $spotifyApi;
        $this->userTrackRepository = $userTrackRepository;
        $this->userRepository = $userRepository;

        parent::__construct($entityManager);
    }

    public function tracks()
    {
        $user = $this->getUser();

        $tracks = $this->userTrackRepository->findAllForUser($user);

        return view(
            'pages.dashboard.tracks',
            [
                'user' => $user,
                'tracks' => $tracks
            ]
        );
    }

    public function tracksOffset(int $offset): JsonResponse
    {
        $tracks = $this->userTrackRepository->findAllForUser($this->getUser(), $offset);

        return new JsonResponse(
            array_map(
                static function (UserTrackInterface $track) {
                    return [
                        'artists' => implode(
                            ', ',
                            $track->getTrack()->getArtists()->map(fn ($a) => $a->getName())->toArray()
                        ),
                        'name' => $track->getTrack()->getName(),
                        'duration' => date('i:s', (int)($track->getTrack()->getDurationMs() / 1000)),
                        'playbackCount' => $track->getPlaybackCount(),
                        'uri' => $track->getTrack()->getUri(),
                        'image' => $track->getTrack()->getAlbum()->getImages()[0]['url'],
                        'addedAt' => (new Carbon($track->getAddedAt()))->diffForHumans(),
                        'albumName' => $track->getTrack()->getAlbum()->getName(),
                        'addToQueue' => route('spotify.queue.add', ['uri' => $track->getTrack()->getUri()]),
                        'statistics' => route('spotify.tracks.show', ['track' => $track->getTrack()->getId()]),
                    ];
                },
                $tracks
            )
        );
    }

    public function profile()
    {
        /** @var User $user */
        $user = $this->cache->remember(
            sprintf('user.%s.profile', $this->getUser()->getSpotifyId()),
            config('cache.ttl'),
            function () {
                $response = $this->spotifyApi->execute(
                    (new GetProfileRequest())->setUser($this->getUser())
                );

                return $response === null ? null : $response->getBody()->getUser();
            }
        );

        $user = $this->userRepository->findOneBySpotifyId($user->getId());

        if ($user !== $this->getUser()) {
            abort(403);
        }

        return view(
            'pages.dashboard.profile',
            [
                'user' => $user,
                'profile' => $user->getProfile()
            ]
        );
    }

    public function toggleWeeklyPlaylists(int $userId): JsonResponse
    {
        $user = $this->userRepository->find($userId);

        if ($user !== $this->getUser()) {
            return new JsonResponse(
                ['success' => false]
            );
        }

        $user->setAutomaticallyCreateWeeklyPlaylist(
            !$user->automaticallyCreateWeeklyPlaylist()
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'success' => true,
                'automaticallyCreateWeeklyPlaylist' => $user->automaticallyCreateWeeklyPlaylist(),
            ]
        );
    }

    public function top(string $type)
    {
        if (!in_array($type, self::TYPE_MAP, true)) {
            return new JsonResponse(
                [
                    'error' => sprintf('Type %s is not valid!', $type)
                ]
            );
        }

        /** @var SpotifyResponseInterface $response */
        $response = $this->cache->tags(
            [
                CacheTags::TRACKS,
                CacheTags::ME . Auth::id()
            ]
        )->remember(
            sprintf('me.%s.top.%s', Auth::id(), $type),
            180,
            function () use ($type) {
                if ($type === self::TYPE_ARTISTS) {
                    $request = new TopArtistsRequest();
                } else {
                    $request = new TopTracksRequest();
                }

                return $this->spotifyApi->execute($request);
            }
        );

        return view(
            sprintf('pages.spotify.top.%s', $type),
            [
                'items' => $response->getBody()->getItems(),
                'type' => array_search($type, self::TYPE_MAP),
            ]
        );
    }

    public function playlists()
    {
        /** @var UserInterface $user */
        $user = Auth::user();

        return view(
            'pages.spotify.playlists',
            [
                'playlists' => $user->getPlaylists(),
            ]
        );
    }

    public function recommended(): Response
    {
        $currentlyPlaying = $this->spotifyApi->execute(new CurrentlyPlayingRequest());

        /** @var Collection $recentlyPlayed */
        $recentlyPlayed = $this->spotifyApi->execute(new GetRecentlyPlayedTracksRequest())->getBody()->getItems();

        $tracks = $recentlyPlayed->slice(0, 4);

        $seedTracks = array_merge(
            array_values($tracks->map(
                static function (RecentlyPlayed $track) {
                    return $track->getTrack()->getId();
                }
            )->toArray()),
            $currentlyPlaying->hasBody() ? [$currentlyPlaying->getBody()->getItem()->getId()] : []
        );

        $recommendationsRequest = new GetRecommendationsRequest(null, null, $seedTracks);

        $recommendations = $this->spotifyApi->execute($recommendationsRequest)->getBody();

        return new JsonResponse($recommendations->toArray());
    }

    public function addToQueue(string $uri): Response
    {
        $this->spotifyApi->execute(new AddItemToQueueRequest($uri));

        return new JsonResponse(
            [
                'success' => true
            ]
        );
    }

    private function getProfileUrl(): string
    {
        return sprintf(
            '%s/%s',
            trim(config('spotify.apiBaseUrl'), '/'),
            self::ENDPOINT_USER_PROFILE
        );
    }

    private function getHeaders(): array
    {
        /** @var UserInterface $user */
        $user = Auth::user();

        return [
            'Authorization' => sprintf(
                'Bearer %s',
                $user->getSpotifyAccessToken()
            )
        ];
    }
}
