<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Spotify\AlbumInterface;
use App\Entities\Spotify\ArtistInterface;
use App\Entities\Spotify\TrackInterface;
use App\Factories\AlbumFactoryInterface;
use App\Factories\ArtistFactoryInterface;
use App\Factories\TrackFactoryInterface;
use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Responses\ResponseBodies\Entity\Album as AlbumEntity;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist as ArtistEntity;
use App\Http\Api\Responses\ResponseBodies\Entity\Copyright;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Http\Api\Responses\ResponseBodies\Entity\Track as TrackEntity;
use App\Repositories\AlbumRepositoryInterface;
use App\Repositories\ArtistRepositoryInterface;
use App\Repositories\TrackRepositoryInterface;
use App\Services\Assigners\TrackArtistAssigner;
use App\Services\Assigners\TrackArtistAssignerInterface;
use App\Services\Providers\Spotify\AlbumProvider;
use App\Services\Providers\Spotify\AlbumProviderInterface;
use App\Services\Providers\Spotify\ArtistProvider;
use App\Services\Providers\Spotify\ArtistProviderInterface;
use App\Services\Providers\Spotify\TrackProvider;
use App\Services\Providers\Spotify\TrackProviderInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Database\Eloquent\Collection;

class UpdateTracksListener
{
    private EntityManager $entityManager;

    private TrackProviderInterface $trackProvider;

    public function __construct(
        EntityManager $entityManager,
        TrackProvider $trackProvider
    ) {
        $this->entityManager = $entityManager;
        $this->trackProvider = $trackProvider;
    }

    public function handle(UpdateTracksEvent $event): void
    {
        $tracks = $event->getTracks();

        /** @var TrackEntity $track */
        foreach ($tracks as $track) {
            if ($track->getId() === null) {
                continue;
            }

            $model = $this->trackProvider->provide($track);
        }
    }
}
