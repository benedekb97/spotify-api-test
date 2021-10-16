<?php

use App\Http\Api\Requests\AddItemToQueueRequest;
use App\Http\Api\Requests\AddTrackToPlaylistRequest;
use App\Http\Api\Requests\CreatePlaylistRequest;
use App\Http\Api\Requests\CurrentlyPlayingRequest;
use App\Http\Api\Requests\GetAlbumTracksRequest;
use App\Http\Api\Requests\GetAvailableDevicesRequest;
use App\Http\Api\Requests\GetCurrentProfileRequest;
use App\Http\Api\Requests\GetGenresRequest;
use App\Http\Api\Requests\GetPlaylistCoverRequest;
use App\Http\Api\Requests\GetPlaylistItemsRequest;
use App\Http\Api\Requests\GetProfileRequest;
use App\Http\Api\Requests\GetRecentlyPlayedTracksRequest;
use App\Http\Api\Requests\GetRecommendationsRequest;
use App\Http\Api\Requests\GetUserPlaylistsRequest;
use App\Http\Api\Requests\GetUserTracksRequest;
use App\Http\Api\Requests\NextTrackRequest;
use App\Http\Api\Requests\PreviousTrackRequest;
use App\Http\Api\Requests\TopArtistsRequest;
use App\Http\Api\Requests\TopTracksRequest;
use App\Http\Api\Requests\TransferPlaybackRequest;
use App\Http\Api\Requests\UploadPlaylistCoverRequest;

return [
    'client' => [
        'id' => env('SPOTIFY_API_CLIENT_ID'),
        'secret' => env('SPOTIFY_API_CLIENT_SECRET'),
    ],
    'baseUrl' => 'https://accounts.spotify.com/',
    'apiBaseUrl' => 'https://api.spotify.com/',
    'redirectUrl' => trim(env('APP_URL'), '/') . '/auth/callback',

    // Register all requests to fetch scopes
    'requests' => [
        AddItemToQueueRequest::class,
        AddTrackToPlaylistRequest::class,
        CreatePlaylistRequest::class,
        CurrentlyPlayingRequest::class,
        GetAlbumTracksRequest::class,
        GetAvailableDevicesRequest::class,
        GetCurrentProfileRequest::class,
        GetGenresRequest::class,
        GetPlaylistCoverRequest::class,
        GetPlaylistItemsRequest::class,
        GetProfileRequest::class,
        GetRecentlyPlayedTracksRequest::class,
        GetRecommendationsRequest::class,
        GetUserPlaylistsRequest::class,
        GetUserTracksRequest::class,
        NextTrackRequest::class,
        PreviousTrackRequest::class,
        TopArtistsRequest::class,
        TopTracksRequest::class,
        TransferPlaybackRequest::class,
        UploadPlaylistCoverRequest::class,
    ],
];
