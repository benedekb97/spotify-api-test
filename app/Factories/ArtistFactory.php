<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Spotify\ArtistInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;

class ArtistFactory extends EntityFactory implements ArtistFactoryInterface
{

}
