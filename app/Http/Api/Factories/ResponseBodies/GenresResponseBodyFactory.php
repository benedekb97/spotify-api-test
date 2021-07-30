<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies;

use App\Http\Api\Responses\ResponseBodies\GenresResponseBody;
use App\Http\Api\Responses\ResponseBodies\ResponseBodyInterface;
use GuzzleHttp\Psr7\Response;

class GenresResponseBodyFactory implements ResponseBodyFactoryInterface
{
    public function create(Response $response): ?ResponseBodyInterface
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $genres = new GenresResponseBody();

        $genres->setGenres($data['genres']);

        return $genres;
    }
}
