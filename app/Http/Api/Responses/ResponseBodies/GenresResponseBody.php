<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

class GenresResponseBody implements ResponseBodyInterface
{
    private ?array $genres = null;

    public function getGenres(): ?array
    {
        return $this->genres;
    }

    public function setGenres(?array $genres): void
    {
        $this->genres = $genres;
    }
}
