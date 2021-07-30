<?php

declare(strict_types=1);

namespace App\Http\Api;

use App\Http\Api\Factories\ResponseBodies\ResponseBodyFactoryInterface;
use App\Http\Api\Requests\SpotifyRequestInterface;
use App\Http\Api\Responses\SpotifyResponseInterface;
use App\Http\Api\Validators\UserRequestScopeValidator;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use LogicException;
use Psr\Log\LogLevel;

class SpotifyApi implements SpotifyApiInterface
{
    private Client $client;

    private Container $container;

    private Logger $logger;

    private UserRequestScopeValidator $requestScopeValidator;

    public function __construct(
        Client $client,
        Container $container,
        Logger $logger,
        UserRequestScopeValidator $requestScopeValidator
    ) {
        $this->client = $client;
        $this->container = $container;
        $this->logger = $logger;
        $this->requestScopeValidator = $requestScopeValidator;
    }

    public function execute(
        SpotifyRequestInterface $request,
        ...$requestBodyFactoryParameters
    ): ?SpotifyResponseInterface
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->requestScopeValidator->validate($user, $request)) {
            $this->logger->log(
                LogLevel::ERROR,
                'User does not have the necessary scopes to complete this request!'
            );

            return null;
        }

        if ($request->requiresRequestBody()) {
            try {
                $requestBodyFactory = $this->container->make($request->getRequestBodyFactoryClass());
            } catch (BindingResolutionException $exception) {
                $this->logger->log(
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
                $requestBodyFactory->create($requestBodyFactoryParameters)
            );
        }

        if ($request->hasResponseBody()) {
            try {
                $responseBodyFactory = $this->container->make($request->getResponseBodyFactoryClass());
            } catch (BindingResolutionException $exception) {
                $this->logger->log(
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

        return $request->getResponse();
    }
}
