<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\Followers;

class FollowersFactory
{
    public function create(array $data): Followers
    {
        $followers = new Followers();

        $followers->setHref($data['href']);
        $followers->setTotal($data['total']);

        return $followers;
    }
}
