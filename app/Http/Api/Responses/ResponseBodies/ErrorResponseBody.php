<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies;

class ErrorResponseBody implements ResponseBodyInterface
{
    private ?array $data = null;

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }
}
