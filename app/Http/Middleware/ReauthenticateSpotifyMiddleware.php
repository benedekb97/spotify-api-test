<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\Spotify\AuthenticationController;
use App\Models\User;
use Closure;
use DateInterval;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReauthenticateSpotifyMiddleware
{
    private Client $client;

    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if (
                isset($user->spotify_refresh_token)
                && (new DateTime()) >= (new DateTime($user->spotify_access_token_expiry))
            ) {

                $this->reauthenticate($user->spotify_refresh_token);
            }
        }

        return $next($request);
    }

    private function reauthenticate(string $refreshToken): void
    {
        $url = sprintf(
            '%s/%s',
            trim(config('spotify.baseUrl'), '/'),
            AuthenticationController::ENDPOINT_ACCESS_TOKEN
        );

        try {
            $response = $this->client->post(
                $url,
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
            Log::error(
                sprintf(
                    'Failed to reauthenticate on spotify using refresh token. Error: %s',
                    $exception->getMessage()
                )
            );

            return;
        }

        $responseContent = $response->getBody()->getContents();

        $response = json_decode($responseContent, true);

        $expiresIn = $response['expires_in'];

        $tokenExpiry = (new DateTime())->add(new DateInterval(sprintf('PT1%sS', $expiresIn)));

        /** @var User $user */
        $user = Auth::user();

        $user->spotify_access_token = $response['access_token'];
        $user->spotify_access_token_expiry = $tokenExpiry->format('Y-m-d H:i:s');

        $user->save();
    }
}
