<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\Entity\Device;
use Illuminate\Database\Eloquent\Collection;

class AvailableDevicesResponseBody implements ResponseBodyInterface
{
    private Collection $devices;

    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): void
    {
        $this->devices->add($device);
    }

    public function __construct()
    {
        $this->devices = new Collection();
    }

    public function toArray(): array
    {
        return [
            'devices' => $this->devices->map(fn (Device $d) => $d->toArray())->toArray(),
        ];
    }
}
