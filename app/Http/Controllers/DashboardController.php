<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Api\Requests\CurrentlyPlayingRequest;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Models\User;
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

        return view(
            'pages.dashboard.index',
            [
                'user' => $user,
                'currentlyPlaying' => $currentlyPlaying->getBody(),
            ]
        );
    }
}
