<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\PlaylistTrackInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;
use App\Factories\TrackAssociationFactoryInterface;
use App\Repositories\PlaylistRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class PlaylistRecommendedTrackProvider implements PlaylistRecommendedTrackProviderInterface
{
    private const TREE_DEPTH = 3;
    private const RECOMMENDATIONS_PER_TRACK = 4;
    private const DEFAULT_MAX = 50;

    private RecommendedTrackProviderInterface $recommendedTrackProvider;

    private TrackAssociationFactoryInterface $trackAssociationFactory;

    private EntityManagerInterface $entityManager;

    private PlaylistRepositoryInterface $playlistRepository;

    public function __construct(
        RecommendedTrackProvider         $recommendedTrackProvider,
        TrackAssociationFactoryInterface $trackAssociationFactory,
        EntityManager                    $entityManager,
        PlaylistRepositoryInterface $playlistRepository
    )
    {
        $this->recommendedTrackProvider = $recommendedTrackProvider;
        $this->trackAssociationFactory = $trackAssociationFactory;
        $this->entityManager = $entityManager;
        $this->playlistRepository = $playlistRepository;
    }

    public function provide(PlaylistInterface $playlist, UserInterface $user, ?int $max = null): array
    {
        if ($playlist->getLocalUser() !== $user) {
            return [];
        }

        /** @var PlaylistTrackInterface $playlistTrack */
        foreach ($playlist->getPlaylistTracks() as $playlistTrack) {
            $track = $playlistTrack->getTrack();

            $this->createTrackAssociationsFromTrack($track, $playlist, $user);
        }

        /** @var PlaylistInterface $playlist */
        $playlist = $this->playlistRepository->find($playlist->getId());

        return array_slice($playlist->getRecommendedTracks(), 0, $max ?? self::DEFAULT_MAX);
    }

    private function createTrackAssociationsFromTrack(
        TrackInterface    $track,
        PlaylistInterface $playlist,
        UserInterface     $user,
        int               $depth = 1
    ): void
    {
        $recommendedTracks = $this->recommendedTrackProvider->provideForUserWithTrack(
            $track,
            $user,
            self::RECOMMENDATIONS_PER_TRACK
        );

        /** @var TrackInterface $recommendedTrack */
        foreach ($recommendedTracks as $recommendedTrack) {
            $trackAssociation = $this->trackAssociationFactory->createFromTracksUserAndPlaylist(
                $track,
                $recommendedTrack,
                $user,
                $playlist
            );

            $this->entityManager->persist($trackAssociation);

            if (self::TREE_DEPTH > $depth) {
                $this->createTrackAssociationsFromTrack($recommendedTrack, $playlist, $user, $depth + 1);
            }
        }

        $this->entityManager->flush();
    }
}
