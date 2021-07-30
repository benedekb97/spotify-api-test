<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Factories\ResponseBodies\Entity\DeviceFactory;
use App\Http\Api\Responses\ResponseBodies\AvailableDevicesResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class AvailableDevicesResponseBodyFactory implements ResponseBodyFactoryInterface
{
    private DeviceFactory $deviceFactory;

    public function __construct(
        DeviceFactory $deviceFactory
    ) {
        $this->deviceFactory = $deviceFactory;
    }

    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $body = new AvailableDevicesResponseBody();

        foreach ($data['devices'] as $device) {
            $body->addDevice(
                $this->deviceFactory->create($device)
            );
        }

        return $body;
    }
}
