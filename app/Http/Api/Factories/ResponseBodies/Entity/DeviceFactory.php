<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\Device;

class DeviceFactory
{
    public function create(array $data): Device
    {
        $device = new Device();

        $device->setId($data['id']);
        $device->setIsActive($data['is_active']);
        $device->setIsPrivateSession($data['is_private_session']);
        $device->setIsRestricted($data['is_restricted']);
        $device->setName($data['name']);
        $device->setType($data['type']);
        $device->setVolumePercentage($data['volume_percent']);

        return $device;
    }
}
