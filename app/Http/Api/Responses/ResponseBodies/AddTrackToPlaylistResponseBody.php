<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

class AddTrackToPlaylistResponseBody implements ResponseBodyInterface
{
    private ?string $snapshotId = null;

    public function setSnapshotId(?string $snapshotId): void
    {
        $this->snapshotId = $snapshotId;
    }

    public function getSnapshotId(): ?string
    {
        return $this->snapshotId;
    }

    public function toArray(): array
    {
        return [
            'snapshotId' => $this->snapshotId,
        ];
    }
}
