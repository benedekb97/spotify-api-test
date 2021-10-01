<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\PlaylistTrackInterface;
use App\Entities\Spotify\TrackInterface;
use Doctrine\Persistence\ObjectRepository;

interface PlaylistTrackRepositoryInterface extends ObjectRepository
{
    public function findByPlaylistAndTrack(PlaylistInterface $playlist, TrackInterface $track): ?PlaylistTrackInterface;
}
