<?php

declare(strict_types=1);

namespace App\Http\Api;

use App\Http\Api\Factories\ResponseBodies\ResponseBodyFactoryInterface;
use App\Http\Api\Requests\SpotifyRequestInterface;
use App\Http\Api\Responses\SpotifyResponseInterface;
use App\Http\Api\Validators\UserRequestScopeValidator;
use App\Services\User\SpotifyReauthenticationService;
use App\Services\User\SpotifyReauthenticationServiceInterface;
use GuzzleHttp\Client;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\LogManager;
use Illuminate\Support\Arr;
use LogicException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class SpotifyApi implements SpotifyApiInterface
{
    private Client $client;

    private Container $container;

    private UserRequestScopeValidator $requestScopeValidator;

    private SpotifyReauthenticationServiceInterface $spotifyReauthenticationService;

    private LoggerInterface $spotifyLogger;

    public function __construct(
        Client                         $client,
        Container                      $container,
        UserRequestScopeValidator      $requestScopeValidator,
        SpotifyReauthenticationService $spotifyReauthenticationService,
        LogManager                     $logManager
    )
    {
        $this->client = $client;
        $this->container = $container;
        $this->requestScopeValidator = $requestScopeValidator;
        $this->spotifyReauthenticationService = $spotifyReauthenticationService;
        $this->spotifyLogger = $logManager->channel('spotifyApi');
    }

    public function execute(
        SpotifyRequestInterface $request,
                                ...$requestBodyFactoryParameters
    ): ?SpotifyResponseInterface
    {
        $this->spotifyLogger->log(
            LogLevel::INFO,
            sprintf(
                'Executing %s!',
                $this->getRequestName($request)
            )
        );

        if ($request->getAccessToken() === null) {
            $user = $request->getUser();

            if ($user === null && $request->getAccessToken() === null) {
                $this->spotifyLogger->log(
                    LogLevel::ERROR,
                    sprintf(
                        'User not set in %s!',
                        $this->getRequestName($request)
                    )
                );

                return null;
            }

            if ($user->needsReauthentication()) {
                $this->spotifyReauthenticationService->reauthenticate($user);
            }

            if (!$this->requestScopeValidator->validate($user, $request)) {
                $this->spotifyLogger->log(
                    LogLevel::ERROR,
                    'User does not have the necessary scopes to complete this request!'
                );

                return null;
            }
        }

        if ($request->requiresRequestBody()) {
            try {
                $requestBodyFactory = $this->container->make($request->getRequestBodyFactoryClass());
            } catch (BindingResolutionException $exception) {
                $this->spotifyLogger->log(
                    LogLevel::ERROR,
                    sprintf(
                        'Could not resolve RequestBodyFactory %s for request %s!',
                        $request->getRequestBodyFactoryClass(),
                        get_class($request)
                    )
                );

                return null;
            }

            $request->setRequestBody(
                $requestBodyFactory->create(...$requestBodyFactoryParameters)
            );
        }

        if ($request->hasResponseBody()) {
            try {
                $responseBodyFactory = $this->container->make($request->getResponseBodyFactoryClass());
            } catch (BindingResolutionException $exception) {
                $this->spotifyLogger->log(
                    LogLevel::ERROR,
                    sprintf(
                        'Could not resolve ResponseBodyFactory %s for request %s!',
                        $request->getResponseBodyFactoryClass(),
                        get_class($request)
                    )
                );

                return null;
            }

            if (!$responseBodyFactory instanceof ResponseBodyFactoryInterface) {
                throw new LogicException(
                    sprintf(
                        'Response body factory %s is not an instance of ResponseBodyFactoryInterface!',
                        get_class($responseBodyFactory)
                    )
                );
            }

            $request->setResponseBodyFactory($responseBodyFactory);
        }

        $request->setClient($this->client);

        $request->execute();

        $this->spotifyLogger->log(
            LogLevel::INFO,
            sprintf(
                'Successfully executed request %s. Response code %s',
                $this->getRequestName($request),
                $request->getResponse()->getStatusCode()
            )
        );

        return $request->getResponse();
    }

    private function getRequestName(SpotifyRequestInterface $request): string
    {
        return Arr::last(
            explode('\\', get_class($request))
        );
    }
}
