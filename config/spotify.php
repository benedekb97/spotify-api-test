<?php

return [
    'client' => [
        'id' => env('SPOTIFY_API_CLIENT_ID'),
        'secret' => env('SPOTIFY_API_CLIENT_SECRET'),
    ],
    'baseUrl' => 'https://accounts.spotify.com/',
    'apiBaseUrl' => 'https://api.spotify.com/',
    'redirectUrl' => trim(env('APP_URL'), '/') . '/spotify/callback',
];
