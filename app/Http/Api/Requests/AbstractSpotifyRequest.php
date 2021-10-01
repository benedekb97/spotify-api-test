<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Entities\UserInterface;
use App\Http\Api\Factories\ResponseBodies\ResponseBodyFactoryInterface;
use App\Http\Api\Requests\RequestBodies\RequestBodyInterface;
use App\Http\Api\Responses\ResponseBodies\ErrorResponseBody;
use App\Http\Api\Responses\SpotifyResponse;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Events\Dispatchable;
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

    private ?string $requestBodyText = null;

    private ?array $headers = null;

    private ?UserInterface $user;

    private ?string $accessToken = null;

    public function __construct()
    {
        /** @var UserInterface $user */
        $user = Auth::user();

        $this->user = $user;
    }

    abstract public function getScopes(): array;

    abstract protected function getEndpoint(): string;

    abstract protected function getMethod(): string;

    abstract protected function getExpectedStatusCode(): ?int;

    abstract protected function validateStatusCode(Response $response): bool;

    abstract protected function getEvents(): array;

    private function getAuthorizationHeader(): array
    {
        return [
            'Authorization' => sprintf(
                'Bearer %s',
                $this->accessToken ?? $this->user->getSpotifyAccessToken()
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

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function setRequestBody(RequestBodyInterface $requestBody): void
    {
        $this->requestBody = $requestBody;
    }

    public function setRequestBodyText(?string $text): void
    {
        $this->requestBodyText = $text;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    private function getOptions(): array
    {
        $options = [];

        if (isset($this->requestBodyText)) {
            $options['body'] = $this->requestBodyText;
        } elseif (isset($this->requestBody)) {
            $options['body'] = json_encode($this->requestBody->toArray());
        }

        $options['headers'] = array_merge($this->headers ?? [], $this->getAuthorizationHeader());

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

    public function hasResponseBody(): bool
    {
        return $this->getResponseBodyFactoryClass() !== null;
    }

    public function requiresRequestBody(): bool
    {
        return $this->getRequestBodyFactoryClass() !== null;
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

            $this->response = new SpotifyResponse();

            $this->response->setStatusCode($response->getStatusCode());
            $this->response->setBody((new ErrorResponseBody())->setData($responseContent));

            Log::error(
                sprintf(
                    'Could not validate status code for response. Status code: %s Error: %s',
                    $response->getStatusCode(),
                    json_encode($responseContent)
                )
            );

            return;
        } elseif (
            $this->getExpectedStatusCode() !== null
            && ($statusCode = $response->getStatusCode()) !== $this->getExpectedStatusCode()
        ) {
            $response = json_decode($response->getBody()->getContents(), true);

            $this->response = new SpotifyResponse();

            $this->response->setStatusCode($statusCode);
            $this->response->setBody((new ErrorResponseBody())->setData($response));

            Log::error(
                sprintf(
                    'Expecting status code %s, received %s. Error: %s',
                    $this->getExpectedStatusCode(),
                    $statusCode,
                    json_encode($response)
                )
            );
        }

        $this->response = new SpotifyResponse();

        if ($this->hasResponseBody()) {
            $this->response->setBody(
                $this->getResponseBodyFactory()->create($response)
            );
        }

        $this->response->setStatusCode($response->getStatusCode());
        $this->response->setHeaders($response->getheaders());

        /**
         * @var Dispatchable $event
         * @var array|null $parameters
         */
        foreach ($this->getEvents() as $event => $parameters) {
            $event::dispatch(...$parameters);
        }
    }
}
