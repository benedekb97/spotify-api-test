<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack;
use DateTime;

class PlaylistTrackFactory
{
    private TrackFactory $trackFactory;

    private PublicUserFactory $userFactory;

    public function __construct(
        TrackFactory $trackFactory,
        PublicUserFactory $userFactory
    ) {
        $this->trackFactory = $trackFactory;
        $this->userFactory = $userFactory;
    }

    public function create(array $data): PlaylistTrack
    {
        $pt = new PlaylistTrack();

        $pt->setTrack(
            $this->trackFactory->create($data['track'])
        );

        $pt->setIsLocal($data['is_local']);
        $pt->setAddedBy(
            $this->userFactory->create($data['added_by'])
        );
        $pt->setAddedAt(new DateTime($data['added_at']));

        return $pt;
    }
}
