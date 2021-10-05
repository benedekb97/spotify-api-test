<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\TrackAssociationInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\UserInterface;

class TrackAssociationFactory extends EntityFactory implements TrackAssociationFactoryInterface
{
    public function createFromTracksUserAndPlaylist(
        TrackInterface $originalTrack,
        TrackInterface $recommendedTrack,
        UserInterface $user,
        PlaylistInterface $playlist
    ): TrackAssociationInterface
    {
        /** @var TrackAssociationInterface $trackAssociation */
        $trackAssociation = $this->createNew();

        $trackAssociation->setOriginalTrack($originalTrack);
        $trackAssociation->setRecommendedTrack($recommendedTrack);
        $trackAssociation->setUser($user);
        $trackAssociation->setPlaylist($playlist);

        return $trackAssociation;
    }
}
