<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateWeeklyRecommendedSongsPlaylistJob
{
    use Dispatchable;

    private SpotifyApiInterface $spotifyApi;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        SpotifyApi $spotifyApi,
        UserRepositoryInterface $userRepository
    )
    {
        $this->spotifyApi = $spotifyApi;
        $this->userRepository = $userRepository;
    }

    public function __invoke(): void
    {
        $users = $this->userRepository->findAllLoggedInWithSpotify();
    }
}
