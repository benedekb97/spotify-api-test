<?php

declare(strict_types=1);

namespace App\Http\Api\Requests\RequestBodies;

class ModifyPlaybackDeviceRequestBody implements RequestBodyInterface
{
    private ?array $deviceIds = null;

    private bool $play = false;

    public function setDeviceIds(array $deviceIds): void
    {
        $this->deviceIds = $deviceIds;
    }

    public function setPlay(bool $play): void
    {
        $this->play = $play;
    }

    public function toArray(): array
    {
        return [
            'device_ids' => $this->deviceIds,
            'play' => $this->play,
        ];
    }
}
