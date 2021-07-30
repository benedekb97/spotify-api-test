<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Factories\ResponseBodies\ResponseBodyFactoryInterface;
use App\Http\Api\Requests\RequestBodies\RequestBodyInterface;
use App\Http\Api\Responses\SpotifyResponse;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use LogicException;

abstract class AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const BASE_URL = 'https://api.spotify.com';

    private ?Client $client = null;

    private ?SpotifyResponseInterface $response = null;

    private ?ResponseBodyFactoryInterface $responseBodyFactory = null;

    private ?RequestBodyInterface $requestBody = null;

    private ?array $headers;

    public function __construct()
    {
        $this->headers = $this->getAuthorizationHeader();
    }

    abstract public function getScopes(): array;

    abstract protected function getEndpoint(): string;

    abstract protected function getMethod(): string;

    abstract protected function getExpectedStatusCode(): ?int;

    abstract protected function validateStatusCode(Response $response): bool;

    private function getAuthorizationHeader(): array
    {
        return [
            'Authorization' => sprintf(
                'Bearer %s',
                Auth::user()->spotify_access_token
            )
        ];
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    private function getClient(): Client
    {
        if (!isset($this->client)) {
            throw new LogicException(
                sprintf('Client not set in %s! Set client before executing request!', get_class($this))
            );
        }

        return $this->client;
    }

    public function setResponseBodyFactory(ResponseBodyFactoryInterface $responseBodyFactory): void
    {
        $this->responseBodyFactory = $responseBodyFactory;
    }

    private function getResponseBodyFactory(): ResponseBodyFactoryInterface
    {
        if (!isset($this->responseBodyFactory)) {
            throw new LogicException(
                sprintf(
                    'Response factory not set in %s! Set response factory before executing request!',
                    get_class($this)
                )
            );
        }

        return $this->responseBodyFactory;
    }

    public function setRequestBody(RequestBodyInterface $requestBody): void
    {
        $this->requestBody = $requestBody;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    private function getOptions(): array
    {
        $options = [];

        if (isset($this->requestBody)) {
            $options['form_params'] = $this->requestBody->toArray();
        }

        $options['headers'] = $this->headers;

        return $options;
    }

    public function getResponse(): ?SpotifyResponseInterface
    {
        return $this->response;
    }

    public function isExecuted(): bool
    {
        return isset($this->response);
    }

    private function buildRequestUrl(): string
    {
        return sprintf(
            '%s/%s',
            trim(self::BASE_URL, '/'),
            $this->getEndpoint()
        );
    }

    public function execute(): void
    {
        try {
            /** @var Response $response */
            $response = $this->getClient()->{$this->getMethod()}(
                $this->buildRequestUrl(),
                $this->getOptions()
            );
        } catch (GuzzleException $exception) {
            Log::error(
                sprintf(
                    '%s failed with message %s',
                    get_class($this),
                    $exception->getMessage()
                )
            );

            return;
        }

        if ($this->getExpectedStatusCode() === null && !$this->validateStatusCode($response)) {
            $responseContent = json_decode($response->getBody()->getContents(), true);

            throw new LogicException(
                sprintf(
                    'Could not validate status code for response. Status code: %s Error: %s',
                    $response->getStatusCode(),
                    json_encode($responseContent)
                )
            );
        } elseif (
            $this->getExpectedStatusCode() !== null
            && ($statusCode = $response->getStatusCode()) !== $this->getExpectedStatusCode()
        ) {
            $response = json_decode($response->getBody()->getContents(), true);

            throw new LogicException(
                sprintf(
                    'Expecting status code %s, received %s. Error: %s',
                    $this->getExpectedStatusCode(),
                    $statusCode,
                    json_encode($response)
                )
            );
        }

        $this->response = new SpotifyResponse();

        $this->response->setBody(
            $this->getResponseBodyFactory()->create($response)
        );
        $this->response->setStatusCode($response->getStatusCode());
        $this->response->setHeaders($response->getheaders());
    }
}
