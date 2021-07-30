<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Factories\ResponseBodies\ResponseBodyFactoryInterface;
use App\Http\Api\Requests\RequestBodies\RequestBodyInterface;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Client;

interface SpotifyRequestInterface
{
    public const METHOD_GET = 'get';
    public const METHOD_POST = 'post';
    public const METHOD_PATCH = 'patch';
    public const METHOD_PUT = 'put';
    public const METHOD_DELETE = 'delete';

    public function execute();

    public function getRequestBodyFactoryClass(): ?string;

    public function requiresRequestBody(): bool;

    public function getResponseBodyFactoryClass(): ?string;

    public function hasResponseBody(): bool;

    public function setResponseBodyFactory(ResponseBodyFactoryInterface $responseBodyFactory): void;

    public function setRequestBody(RequestBodyInterface $requestBody): void;

    public function setClient(Client $client): void;

    public function getResponse(): ?SpotifyResponseInterface;
}
