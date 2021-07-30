<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Http\Api\Requests\CurrentlyPlayingRequest;
use App\Http\Api\Requests\NextTrackRequest;
use App\Http\Api\Requests\PreviousTrackRequest;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends Controller
{
    private SpotifyApiInterface $spotifyApi;

    public function __construct(
        SpotifyApi $spotifyApi
    ) {
        $this->spotifyApi = $spotifyApi;
    }

    public function next(): Response
    {
        $this->spotifyApi->execute(new NextTrackRequest());

        return new JsonResponse(['success' => true]);
    }

    public function previous(): Response
    {
        $this->spotifyApi->execute(new PreviousTrackRequest());

        return new JsonResponse(['success' => true]);
    }

    public function currentlyPlaying(): Response
    {
        $currentlyPlaying = $this->spotifyApi->execute(new CurrentlyPlayingRequest());

        return new JsonResponse(
            $currentlyPlaying->getBody() !== null
                ? $currentlyPlaying->getBody()->toArray()
                : null
        );
    }
}
