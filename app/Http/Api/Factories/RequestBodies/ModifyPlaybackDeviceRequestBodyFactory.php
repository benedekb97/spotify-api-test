<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\RequestBodies;

use App\Http\Api\Requests\RequestBodies\ModifyPlaybackDeviceRequestBody;

class ModifyPlaybackDeviceRequestBodyFactory
{
    public function create(
        array $deviceIds,
        bool $play = false
    ): ModifyPlaybackDeviceRequestBody{
        $body = new ModifyPlaybackDeviceRequestBody();

        $body->setDeviceIds($deviceIds);
        $body->setPlay($play);

        return $body;
    }
}
