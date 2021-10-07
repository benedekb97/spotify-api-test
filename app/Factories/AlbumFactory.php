<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\AlbumInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Album as AlbumEntity;
use App\Http\Api\Responses\ResponseBodies\Entity\Copyright;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;

class AlbumFactory extends EntityFactory implements AlbumFactoryInterface
{

}
