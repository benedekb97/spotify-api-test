<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;
use App\Repositories\PlaylistRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\Compilers\PlaylistRecommendedTrackCompiler;
use App\Services\Compilers\PlaylistRecommendedTrackCompilerInterface;
use App\Services\Generators\RecommendedPlaylistGenerator;
use App\Services\Generators\RecommendedPlaylistGeneratorInterface;
use App\Services\User\SpotifyReauthenticationService;
use App\Services\User\SpotifyReauthenticationServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateRecommendedPlaylistCommand extends Command
{
    private const TRACK_COUNT = 50;

    private const ARGUMENT_PLAYLIST_ID = 'playlist';

    protected $name = 'playlist';

    private UserRepositoryInterface $userRepository;

    private SpotifyReauthenticationServiceInterface $spotifyReauthenticationService;

    private PlaylistRepositoryInterface $playlistRepository;

    private PlaylistRecommendedTrackCompilerInterface $playlistRecommendedTrackCompiler;

    private RecommendedPlaylistGeneratorInterface $recommendedPlaylistGenerator;

    public function __construct(
        UserRepositoryInterface          $userRepository,
        SpotifyReauthenticationService   $spotifyReauthenticationService,
        PlaylistRepositoryInterface      $playlistRepository,
        PlaylistRecommendedTrackCompiler $playlistRecommendedTrackCompiler,
        RecommendedPlaylistGenerator     $recommendedPlaylistGenerator
    )
    {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->spotifyReauthenticationService = $spotifyReauthenticationService;
        $this->playlistRepository = $playlistRepository;
        $this->playlistRecommendedTrackCompiler = $playlistRecommendedTrackCompiler;
        $this->recommendedPlaylistGenerator = $recommendedPlaylistGenerator;
    }

    public function configure(): void
    {
        $this->addArgument(
            self::ARGUMENT_PLAYLIST_ID,
            InputArgument::REQUIRED,
            'ID of the playlist seed'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAllLoggedInWithSpotify();

        /** @var PlaylistInterface $playlist */
        $playlist = $this->playlistRepository->find($input->getArgument(self::ARGUMENT_PLAYLIST_ID));

        if ($playlist === null) {
            $output->writeln('Playlist could not be found!');

            return self::FAILURE;
        }

        /** @var UserInterface $user */
        foreach ($users as $user) {
            if ($user->needsReauthentication()) {
                $this->spotifyReauthenticationService->reauthenticate($user);
            }

            $playlists = $user->getPlaylists();

            if (!$playlists->contains($playlist)) {
                continue;
            }

            $recommendedTracks = $this->playlistRecommendedTrackCompiler->compile($playlist);

            $newTracks = new ArrayCollection();

            foreach ($recommendedTracks as $track) {
                /** @var TrackInterface $track */
                $track = $track['track'];

                if (!$playlist->hasTrack($track)) {
                    $newTracks->add($track);
                }

                if ($newTracks->count() === self::TRACK_COUNT) {
                    break;
                }
            }

            $newPlaylist = $this->recommendedPlaylistGenerator->createPlaylist($user, $playlist);

            $trackUris = $newTracks->map(fn(TrackInterface $t) => $t->getUri())->toArray();

            $this->recommendedPlaylistGenerator->addTracks($newPlaylist, $user, $trackUris);
        }

        return self::SUCCESS;
    }
}
