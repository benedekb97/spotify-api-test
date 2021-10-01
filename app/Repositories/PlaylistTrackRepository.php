<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\PlaylistTrackInterface;
use App\Entities\Spotify\TrackInterface;

class PlaylistTrackRepository extends EntityRepository implements PlaylistTrackRepositoryInterface
{
    public function findByPlaylistAndTrack(PlaylistInterface $playlist, TrackInterface $track): ?PlaylistTrackInterface
    {
        return $this->createQueryBuilder('o')
            ->where('o.playlist = :playlist')
            ->andWhere('o.track = :track')
            ->setParameter('playlist', $playlist)
            ->setParameter('track', $track)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
