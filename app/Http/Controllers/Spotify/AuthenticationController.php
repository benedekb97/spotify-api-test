<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateInterval;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    private const SCOPE_UGC_IMAGE_UPLOAD = 'ugc-image-upload';

    private const SCOPE_LISTENING_HISTORY_RECENTLY_PLAYED = 'user-read-recently-played';
    private const SCOPE_LISTENING_HISTORY_TOP_PLAYED = 'user-top-read';
    private const SCOPE_LISTENING_HISTORY_PLAYBACK_POSITION = 'user-read-playback-position';

    private const SCOPE_SPOTIFY_CONNECT_PLAYBACK_STATE = 'user-read-playback-state';
    private const SCOPE_SPOTIFY_CONNECT_MODIFY_PLAYBACK_STATE = 'user-modify-playback-state';
    private const SCOPE_SPOTIFY_CONNECT_CURRENTLY_PLAYING = 'user-read-currently-playing';

    private const SCOPE_PLAYBACK_REMOTE_CONTROL = 'app-remote-control';
    private const SCOPE_PLAYBACK_STREAMING = 'streaming';

    private const SCOPE_PLAYLISTS_MODIFY_PUBLIC = 'playlist-modify-public';
    private const SCOPE_PLAYLISTS_MODIFY_PRIVATE = 'playlist-modify-private';
    private const SCOPE_PLAYLISTS_READ_PRIVATE = 'playlist-read-private';
    private const SCOPE_PLAYLISTS_READ_COLLABORATIVE = 'playlist-read-collaborative';

    private const SCOPE_FOLLOW_MODIFY = 'user-follow-modify';
    private const SCOPE_FOLLOW_READ = 'user-follow-read';

    private const SCOPE_LIBRARY_MODIFY = 'user-library-modify';
    private const SCOPE_LIBRARY_READ = 'user-library-read';

    private const SCOPE_USERS_READ_EMAIL = 'user-read-email';
    private const SCOPE_USERS_READ_PRIVATE = 'user-read-private';

    private const SCOPE_ENABLED_MAP = [
        self::SCOPE_UGC_IMAGE_UPLOAD => false,

        self::SCOPE_LISTENING_HISTORY_RECENTLY_PLAYED => false,
        self::SCOPE_LISTENING_HISTORY_TOP_PLAYED => true,
        self::SCOPE_LISTENING_HISTORY_PLAYBACK_POSITION => false,

        self::SCOPE_SPOTIFY_CONNECT_PLAYBACK_STATE => false,
        self::SCOPE_SPOTIFY_CONNECT_MODIFY_PLAYBACK_STATE => false,
        self::SCOPE_SPOTIFY_CONNECT_CURRENTLY_PLAYING => false,

        self::SCOPE_PLAYBACK_REMOTE_CONTROL => false,
        self::SCOPE_PLAYBACK_STREAMING => false,

        self::SCOPE_PLAYLISTS_MODIFY_PUBLIC => false,
        self::SCOPE_PLAYLISTS_MODIFY_PRIVATE => false,
        self::SCOPE_PLAYLISTS_READ_PRIVATE => false,
        self::SCOPE_PLAYLISTS_READ_COLLABORATIVE => false,

        self::SCOPE_FOLLOW_MODIFY => false,
        self::SCOPE_FOLLOW_READ => false,

        self::SCOPE_LIBRARY_MODIFY => false,
        self::SCOPE_LIBRARY_READ => false,

        self::SCOPE_USERS_READ_EMAIL => true,
        self::SCOPE_USERS_READ_PRIVATE => true,
    ];

    public const ENDPOINT_AUTHORIZE = 'authorize';
    public const ENDPOINT_ACCESS_TOKEN = 'api/token';

    public const SESSION_STATE_KEY = 'spotify.state';

    private Client $client;

    public function __construct(
        Client $client
    ){
        $this->client = $client;
    }

    public function redirect(): Response
    {
        return new RedirectResponse($this->getRedirectUrl());
    }

    public function callback(Request $request): Response
    {
        $state = Session::get(self::SESSION_STATE_KEY);

        if (!$request->has('state') || $state !== $request->get('state')) {
            return new JsonResponse(
                [
                    'error' => 'State provided by spotify redirect does not match state stored in session. Aborting.'
                ]
            );
        }

        if ($request->has('code')) {
            $accessCode = $request->get('code');

            try {
                $response = $this->client->post(
                    $this->getTokenRequestUrl(),
                    [
                        'form_params' => [
                            'grant_type' => 'authorization_code',
                            'code' => $accessCode,
                            'redirect_uri' => config('spotify.redirectUrl'),
                            'client_id' => config('spotify.client.id'),
                            'client_secret' => config('spotify.client.secret'),
                        ]
                    ]
                );
            } catch (GuzzleException $exception) {
                return new JsonResponse(
                    [
                        'error' => $exception->getMessage()
                    ]
                );
            }

            $responseContent = $response->getBody()->getContents();

            $response = json_decode($responseContent, true);

            $expiresIn = $response['expires_in'];

            $tokenExpiry = (new DateTime())->add(new DateInterval(sprintf('PT1%sS', $expiresIn)));

            /** @var User $user */
            $user = Auth::user();

            $user->spotify_access_token = $response['access_token'];
            $user->spotify_refresh_token = $response['refresh_token'];
            $user->spotify_access_token_expiry = $tokenExpiry;

            $user->save();

            return new RedirectResponse(route('dashboard.index'));
        }

        if ($request->has('error')) {
            return new JsonResponse(
                [
                    'error' => $request->get('error')
                ]
            );
        }

        return new JsonResponse(
            [
                'error' => 'Unknown error occurred. Aborting.'
            ]
        );
    }

    private function getTokenRequestUrl(): string
    {
        $baseUrl = trim(config('spotify.baseUrl'), '/');

        return sprintf(
            '%s/%s',
            $baseUrl,
            self::ENDPOINT_ACCESS_TOKEN
        );
    }

    private function getRedirectUrl(): string
    {
        $scope = $this->getScope();

        $baseUrl = trim(config('spotify.baseUrl'), '/');

        $state = Str::random();

        Session::put(self::SESSION_STATE_KEY, $state);

        $queryString = str_replace(
            '+',
            ' ',
            http_build_query(
                [
                    'client_id' => config('spotify.client.id'),
                    'response_type' => 'code',
                    'redirect_uri' => config('spotify.redirectUrl'),
                    'scope' => $scope,
                    'state' => $state
                ]
            )
        );

        return sprintf(
            '%s/%s?%s',
            $baseUrl,
            self::ENDPOINT_AUTHORIZE,
            $queryString
        );
    }

    private function getScope(): string
    {
        $scopes = [];

        foreach (self::SCOPE_ENABLED_MAP as $scope => $enabled) {
            if ($enabled) {
                $scopes[] = $scope;
            }
        }

        return implode(' ', $scopes);
    }
}
