<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\ExplicitContentSettings;

class ExplicitContentSettingsFactory
{
    public function create(array $data): ExplicitContentSettings
    {
        $ecs = new ExplicitContentSettings();

        $ecs->setFilterEnabled($data['filter_enabled']);
        $ecs->setFilterLocked($data['filter_locked']);

        return $ecs;
    }
}
