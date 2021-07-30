<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Api\Requests\CurrentlyPlayingRequest;
use App\Http\Api\Requests\GetGenresRequest;
use App\Http\Api\Requests\GetRecentlyPlayedTracksRequest;
use App\Http\Api\Requests\GetRecommendationsRequest;
use App\Http\Api\Responses\ResponseBodies\Entity\RecentlyPlayed;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private SpotifyApiInterface $spotifyApi;

    public function __construct(
        SpotifyApi $spotifyApi
    ) {
        $this->spotifyApi = $spotifyApi;
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $currentlyPlaying = $this->spotifyApi->execute(new CurrentlyPlayingRequest());

        /** @var Collection $recentlyPlayed */
        $recentlyPlayed = $this->spotifyApi->execute(new GetRecentlyPlayedTracksRequest())->getBody()->getItems();

        $tracks = $recentlyPlayed->slice(0, 1);

        $seedArtists = array_merge(
            array_values($tracks->map(
                static function (RecentlyPlayed $track) {
                    return $track->getTrack()->getArtists()->first()->getId();
                }
            )->toArray()),
            [$currentlyPlaying->getBody()->getItem()->getArtists()->first()->getId()]
        );

        $seedTracks = array_merge(
            array_values($tracks->map(
                static function (RecentlyPlayed $track) {
                    return $track->getTrack()->getId();
                }
            )->toArray()),
            [$currentlyPlaying->getBody()->getItem()->getId()]
        );

        $recommendationsRequest = new GetRecommendationsRequest($seedArtists, null, $seedTracks);

        $recommendations = $this->spotifyApi->execute($recommendationsRequest)->getBody();

        return view(
            'pages.dashboard.index',
            [
                'user' => $user,
                'currentlyPlaying' => $currentlyPlaying->getBody(),
                'recommendations' => $recommendations
            ]
        );
    }
}
