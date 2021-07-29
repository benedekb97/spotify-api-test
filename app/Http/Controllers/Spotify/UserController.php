<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Util\CacheTags;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Cache\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Json;
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

    public function __construct(
        Client $client,
        Repository $cache
    ) {
        $this->client = $client;
        $this->cache = $cache;
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

        $response =  $this->cache->tags(
            [
                CacheTags::TRACKS,
                CacheTags::ME . Auth::id()
            ]
        )->remember(
            sprintf('me.%s.top.%s', Auth::id(), $type),
            180,
            function () use ($type) {
                try {
                    $response = $this->client->get(
                        $this->getTopUrl($type),
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

                return json_decode($response->getBody()->getContents(), true);
            }
        );

        return view(
            sprintf('pages.spotify.top.%s', $type),
            [
                'items' => $response['items'],
                'type' => array_search($type, self::TYPE_MAP),
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

    private function getTopUrl(string $type): string
    {
        return sprintf(
            '%s/%s/%s?limit=50',
            trim(config('spotify.apiBaseUrl'), '/'),
            self::ENDPOINT_TOP_ARTISTS_AND_TRACKS,
            $type
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
