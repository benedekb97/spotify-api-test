<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\UserInterface;
use App\Repositories\PlaylistRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\Providers\PlaylistRecommendedTrackProvider;
use App\Services\Providers\PlaylistRecommendedTrackProviderInterface;
use App\Services\User\SpotifyReauthenticationService;
use App\Services\User\SpotifyReauthenticationServiceInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateRecommendationTreeCommand extends Command
{
    protected $name = 'tree';

    private PlaylistRepositoryInterface $playlistRepository;

    private UserRepositoryInterface $userRepository;

    private SpotifyReauthenticationServiceInterface $spotifyReauthenticationService;

    private PlaylistRecommendedTrackProviderInterface $playlistRecommendedTrackProvider;

    public function __construct(
        PlaylistRepositoryInterface      $playlistRepository,
        UserRepositoryInterface          $userRepository,
        SpotifyReauthenticationService   $spotifyReauthenticationService,
        PlaylistRecommendedTrackProvider $playlistRecommendedTrackProvider
    )
    {
        parent::__construct();

        $this->playlistRepository = $playlistRepository;
        $this->userRepository = $userRepository;
        $this->spotifyReauthenticationService = $spotifyReauthenticationService;
        $this->playlistRecommendedTrackProvider = $playlistRecommendedTrackProvider;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAllLoggedInWithSpotify();

        /** @var UserInterface $user */
        foreach ($users as $user) {
            if ($user->needsReauthentication()) {
                $this->spotifyReauthenticationService->reauthenticate($user);
            }

            /** @var array<PlaylistInterface> $playlists */
            $playlists = $this->playlistRepository->getTopPlayedPlaylistsForUser($user);

            $playlists = array_filter(
                $playlists,
                static function (PlaylistInterface $playlist): bool {
                    return $playlist->getTrackAssociations()->isEmpty();
                }
            );

            /** @var PlaylistInterface $playlist */
            foreach ($playlists as $playlist) {
                $this->playlistRecommendedTrackProvider->provide($playlist, $user);

                return self::SUCCESS;
            }
        }

        return self::SUCCESS;
    }
}
