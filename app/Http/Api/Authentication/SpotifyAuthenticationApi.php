<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication;

use App\Http\Api\Authentication\Factory\Response\AccessTokenResponseFactory;
use App\Http\Api\Authentication\Factory\Response\RefreshedAccessTokenResponseFactory;
use App\Http\Api\Authentication\Responses\AccessTokenResponse;
use App\Http\Api\Authentication\Responses\RefreshedAccessTokenResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class SpotifyAuthenticationApi implements SpotifyAuthenticationApiInterface
{
    private AccessTokenResponseFactory $accessTokenResponseFactory;

    private RefreshedAccessTokenResponseFactory $refreshedAccessTokenResponseFactory;

    private Client $client;

    private Logger $logger;

    public function __construct(
        AccessTokenResponseFactory $accessTokenResponseFactory,
        RefreshedAccessTokenResponseFactory $refreshedAccessTokenResponseFactory,
        Client $client,
        Logger $logger
    ) {
        $this->accessTokenResponseFactory = $accessTokenResponseFactory;
        $this->refreshedAccessTokenResponseFactory = $refreshedAccessTokenResponseFactory;
        $this->client = $client;
        $this->logger = $logger;
    }

    public function redirect(): Response
    {
        return new RedirectResponse($this->getRedirectUrl());
    }

    public function getAccessToken(string $accessCode): ?AccessTokenResponse
    {
        try {
            $response = $this->client->post(
                $this->getTokenRequestUrl(),
                [
                    'form_params' => [
                        'grant_type' => 'authorization_code',
                        'code' => $accessCode,
                        'redirect_uri' => config('spotify.redirectUrl'),
                        'client_id' => config('spotify.client.id'),
                        'client_secret' => config('spotify.client.secret'),
                    ]
                ]
            );
        } catch (GuzzleException $exception) {
            $this->logger->log(
                LogLevel::ERROR,
                sprintf(
                    'Access Token request failed with message: %s',
                    $exception->getMessage()
                )
            );

            return null;
        }

        $responseContent = $response->getBody()->getContents();

        $response = json_decode($responseContent, true);

        return $this->accessTokenResponseFactory->create($response);
    }

    public function refreshAccessToken(string $refreshToken): ?RefreshedAccessTokenResponse
    {
        try {
            $response = $this->client->post(
                $this->getTokenRequestUrl(),
                [
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refreshToken,
                        'client_id' => config('spotify.client.id'),
                        'client_secret' => config('spotify.client.secret'),
                    ]
                ]
            );
        } catch (GuzzleException $exception) {
            $this->logger->log(
                LogLevel::ERROR,
                sprintf(
                    'Failed to reauthenticate on spotify using refresh token. Error: %s',
                    $exception->getMessage()
                )
            );

            return null;
        }

        $response = json_decode($response->getBody()->getContents(), true);

        return $this->refreshedAccessTokenResponseFactory->create($response);
    }

    private function getRedirectUrl(): string
{
    $scope = $this->getScope();

    $baseUrl = trim(self::BASE_URL, '/');

    $state = Str::random();

    Session::put(self::SESSION_STATE_KEY, $state);

    $queryString = str_replace(
        '+',
        ' ',
        http_build_query(
            [
                'client_id' => config('spotify.client.id'),
                'response_type' => 'code',
                'redirect_uri' => config('spotify.redirectUrl'),
                'scope' => $scope,
                'state' => $state
            ]
        )
    );

    return sprintf(
        '%s/%s?%s',
        $baseUrl,
        self::ENDPOINT_AUTHORIZE,
        $queryString
    );
}

    private function getScope(): string
    {
        $scopes = [];

        foreach (self::SCOPE_ENABLED_MAP as $scope => $enabled) {
            if ($enabled) {
                $scopes[] = $scope;
            }
        }

        return implode(' ', $scopes);
    }

    private function getTokenRequestUrl(): string
    {
        $baseUrl = trim(self::BASE_URL, '/');

        return sprintf(
            '%s/%s',
            $baseUrl,
            self::ENDPOINT_ACCESS_TOKEN
        );
    }
}
