<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication;

use App\Http\Api\Authentication\Responses\AccessTokenResponse;
use App\Http\Api\Authentication\Responses\RefreshedAccessTokenResponse;
use Symfony\Component\HttpFoundation\Response;

interface SpotifyAuthenticationApiInterface
{
    public const BASE_URL = 'https://accounts.spotify.com/';

    public const SCOPE_UGC_IMAGE_UPLOAD = 'ugc-image-upload';

    public const SCOPE_LISTENING_HISTORY_RECENTLY_PLAYED = 'user-read-recently-played';
    public const SCOPE_LISTENING_HISTORY_TOP_PLAYED = 'user-top-read';
    public const SCOPE_LISTENING_HISTORY_PLAYBACK_POSITION = 'user-read-playback-position';

    public const SCOPE_SPOTIFY_CONNECT_PLAYBACK_STATE = 'user-read-playback-state';
    public const SCOPE_SPOTIFY_CONNECT_MODIFY_PLAYBACK_STATE = 'user-modify-playback-state';
    public const SCOPE_SPOTIFY_CONNECT_CURRENTLY_PLAYING = 'user-read-currently-playing';

    public const SCOPE_PLAYBACK_REMOTE_CONTROL = 'app-remote-control';
    public const SCOPE_PLAYBACK_STREAMING = 'streaming';

    public const SCOPE_PLAYLISTS_MODIFY_PUBLIC = 'playlist-modify-public';
    public const SCOPE_PLAYLISTS_MODIFY_PRIVATE = 'playlist-modify-private';
    public const SCOPE_PLAYLISTS_READ_PRIVATE = 'playlist-read-private';
    public const SCOPE_PLAYLISTS_READ_COLLABORATIVE = 'playlist-read-collaborative';

    public const SCOPE_FOLLOW_MODIFY = 'user-follow-modify';
    public const SCOPE_FOLLOW_READ = 'user-follow-read';

    public const SCOPE_LIBRARY_MODIFY = 'user-library-modify';
    public const SCOPE_LIBRARY_READ = 'user-library-read';

    public const SCOPE_USERS_READ_EMAIL = 'user-read-email';
    public const SCOPE_USERS_READ_PRIVATE = 'user-read-private';

    public const SCOPE_ENABLED_MAP = [
        self::SCOPE_UGC_IMAGE_UPLOAD => false,

        self::SCOPE_LISTENING_HISTORY_RECENTLY_PLAYED => true,
        self::SCOPE_LISTENING_HISTORY_TOP_PLAYED => true,
        self::SCOPE_LISTENING_HISTORY_PLAYBACK_POSITION => true,

        self::SCOPE_SPOTIFY_CONNECT_PLAYBACK_STATE => true,
        self::SCOPE_SPOTIFY_CONNECT_MODIFY_PLAYBACK_STATE => true,
        self::SCOPE_SPOTIFY_CONNECT_CURRENTLY_PLAYING => true,

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

    public function redirect(): Response;

    public function getAccessToken(string $accessCode): ?AccessTokenResponse;

    public function refreshAccessToken(string $refreshToken): ?RefreshedAccessTokenResponse;
}
