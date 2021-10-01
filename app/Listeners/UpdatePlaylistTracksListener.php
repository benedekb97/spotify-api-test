<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\TrackInterface;
use App\Factories\PlaylistTrackFactoryInterface;
use App\Http\Api\Events\UpdatePlaylistTracksEvent;
use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack as PlaylistTrackEntity;
use App\Repositories\PlaylistRepositoryInterface;
use App\Repositories\PlaylistTrackRepositoryInterface;
use App\Repositories\TrackRepositoryInterface;
use Doctrine\ORM\EntityManager;

class UpdatePlaylistTracksListener
{
    private PlaylistRepositoryInterface $playlistRepository;

    private TrackRepositoryInterface $trackRepository;

    private PlaylistTrackFactoryInterface $playlistTrackFactory;

    private PlaylistTrackRepositoryInterface $playlistTrackRepository;

    private EntityManager $entityManager;

    public function __construct(
        PlaylistRepositoryInterface      $playlistRepository,
        TrackRepositoryInterface         $trackRepository,
        PlaylistTrackFactoryInterface    $playlistTrackFactory,
        PlaylistTrackRepositoryInterface $playlistTrackRepository,
        EntityManager $entityManager
    )
    {
        $this->playlistRepository = $playlistRepository;
        $this->trackRepository = $trackRepository;
        $this->playlistTrackFactory = $playlistTrackFactory;
        $this->playlistTrackRepository = $playlistTrackRepository;
        $this->entityManager = $entityManager;
    }

    public function handle(UpdatePlaylistTracksEvent $event): void
    {
        $playlistId = $event->getPlaylistId();
        $playlistTracks = $event->getPlaylistTracks();

        /** @var PlaylistInterface $playlist */
        $playlist = $this->playlistRepository->find($playlistId);

        /** @var PlaylistTrackEntity $playlistTrack */
        foreach ($playlistTracks as $playlistTrack) {
            /** @var TrackInterface $track */
            $track = $this->trackRepository->find($playlistTrack->getTrack()->getId());

            $entity = $this->playlistTrackRepository->findByPlaylistAndTrack($playlist, $track);

            if ($entity === null) {
                $entity = $this->playlistTrackFactory->createFromSpotifyEntity(
                    $playlistTrack,
                    $track,
                    $playlist
                );
            }

            $entity->setAddedByUserId($playlistTrack->getAddedBy()->getId());
            $entity->setAddedAt($playlistTrack->getAddedAt());
            $entity->setLocal($playlistTrack->isLocal());

            $playlist->addPlaylistTrack($entity);

            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }
}
