<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Factories\ResponseBodies\GetUserPlaylistsResponseBodyFactory;
use App\Http\Api\Requests\GetUserPlaylistsRequest;
use App\Http\Api\Responses\ResponseBodies\GetUserPlaylistsResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SynchronizePlaylistsJob
{
    use Dispatchable;

    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private SpotifyApiInterface $spotifyApi;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        SpotifyApi $spotifyApi
    ) {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->spotifyApi = $spotifyApi;
    }

    public function __invoke()
    {
        $users = User::all()->filter(
            static function (User $user): bool {
                return $user->isLoggedInWithSpotify();
            }
        );

        /** @var User $user */
        foreach ($users as $user) {
            Log::info('Synchronizing playlists for user ' . $user->id);

            if ($user->needsReauthentication()) {
                $this->reauthenticateUser($user);
            }

            $offset = 0;

            do {
                if (isset($responseBody)) {
                    $offset += $responseBody->getLimit();
                }

                $response = $this->spotifyApi->execute(
                    (new GetUserPlaylistsRequest($offset))->setUser($user)
                );

                /** @var GetUserPlaylistsResponseBody $responseBody */
                $responseBody = $response->getBody();
            } while ($responseBody->getLimit() + $offset < $responseBody->getTotal());
        }

        Log::info('Finished synchronizing playlists');
    }

    private function reauthenticateUser(User $user): void
    {
        $response = $this->spotifyAuthenticationApi->refreshAccessToken($user->spotify_refresh_token);

        $user->spotify_access_token = $response->getAccessToken();
        $user->spotify_access_token_expiry = (new DateTime())
            ->add(new DateInterval(sprintf('PT%sS', $response->getExpiresIn())))
            ->format('Y-m-d H:i:s');

        $user->save();
    }
}
