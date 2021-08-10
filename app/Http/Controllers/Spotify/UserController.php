<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Requests\AbstractSpotifyRequest;
use App\Http\Api\Requests\AddItemToQueueRequest;
use App\Http\Api\Requests\CurrentlyPlayingRequest;
use App\Http\Api\Requests\GetRecentlyPlayedTracksRequest;
use App\Http\Api\Requests\GetRecommendationsRequest;
use App\Http\Api\Requests\GetUserPlaylistsRequest;
use App\Http\Api\Requests\TopArtistsRequest;
use App\Http\Api\Requests\TopTracksRequest;
use App\Http\Api\Responses\ResponseBodies\Entity\RecentlyPlayed;
use App\Http\Api\Responses\ResponseBodies\GetUserPlaylistsResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use App\Jobs\CreateWeeklyMostPlayedPlaylistJob;
use App\Models\User;
use App\Util\CacheTags;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private const ENDPOINT_USER_PROFILE = 'v1/me';
    private const ENDPOINT_TOP_ARTISTS_AND_TRACKS = 'v1/me/top';

    private const TYPE_TRACKS = 'tracks';
    private const TYPE_ARTISTS = 'artists';

    private const TYPE_MAP = [
        'Tracks' => self::TYPE_TRACKS,
        'Artists' => self::TYPE_ARTISTS,
    ];

    private Client $client;

    private Repository $cache;

    private SpotifyApiInterface $spotifyApi;

    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    public function __construct(
        Client $client,
        Repository $cache,
        SpotifyApi $spotifyApi,
        SpotifyAuthenticationApi $spotifyAuthenticationApi
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->spotifyApi = $spotifyApi;
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
    }

    public function profile(): Response
    {
        try {
            $response = $this->client->get(
                $this->getProfileUrl(),
                [
                    'headers' => $this->getHeaders(),
                ]
            );
        } catch (GuzzleException $exception) {
            return new JsonResponse(
                [
                    'error' => $exception->getMessage()
                ]
            );
        }

        $responseContent = json_decode($response->getBody()->getContents(), true);

        return new JsonResponse($responseContent);
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
        $user = Auth::user();

        return view(
            'pages.spotify.playlists',
            [
                'playlists' => $user->playlists()->orderBy('name')->get(),
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
        /** @var User $user */
        $user = Auth::user();

        return [
            'Authorization' => sprintf(
                'Bearer %s',
                $user->spotify_access_token
            )
        ];
    }
}
