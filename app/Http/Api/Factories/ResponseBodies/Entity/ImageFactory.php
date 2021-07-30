<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\Image;

class ImageFactory
{
    public function create(array $data): Image
    {
        $image = new Image();

        $image->setHeight($data['height']);
        $image->setWidth($data['width']);
        $image->setUrl($data['url']);

        return $image;
    }
}
