<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\SavedTrack;
use DateTime;

class SavedTrackFactory
{
    private TrackFactory $trackFactory;

    public function __construct(
        TrackFactory $trackFactory
    ) {
        $this->trackFactory = $trackFactory;
    }

    public function create(array $data): SavedTrack
    {
        $savedTrack = new SavedTrack();

        $savedTrack->setAddedAt(new DateTime($data['added_at']));
        $savedTrack->setTrack(
            $this->trackFactory->create($data['track'])
        );

        return $savedTrack;
    }
}
