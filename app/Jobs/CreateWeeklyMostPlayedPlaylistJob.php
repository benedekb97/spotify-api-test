<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Requests\AddTrackToPlaylistRequest;
use App\Http\Api\Requests\CreatePlaylistRequest;
use App\Http\Api\Responses\ResponseBodies\CreatePlaylistResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Models\Spotify\Playlist;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateWeeklyMostPlayedPlaylistJob
{
    use Dispatchable;

    private const MAX_SONG_COUNT = 25;

    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    private SpotifyApiInterface $spotifyApi;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        SpotifyApi $spotifyApi
    )
    {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
        $this->spotifyApi = $spotifyApi;
    }

    public function __invoke()
    {
        $users = User::all()->filter(
            static function (User $user): bool {
                return $user->isLoggedInWithSpotify() && $user->automatically_create_weekly_playlist;
            }
        );

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->needsReauthentication()) {
                $this->reauthenticateUser($user);
            }

            $playlistRequest = new CreatePlaylistRequest($user->spotify_id);
            $playlistRequest->setUser($user);

            $playlistResponse = $this->spotifyApi->execute(
                $playlistRequest,
                $this->getPlaylistName(),
                false,
                false,
                $this->getPlaylistDescription()
            );

            /** @var CreatePlaylistResponseBody $responseBody */
            $responseBody = $playlistResponse->getBody();

            $playlist = Playlist::createFromEntity($responseBody->getPlaylist());

            $tracks = [];

            $playbacks = $user->playbacks()
                ->whereBetween(
                    'played_at',
                    [new DateTime('-1 week'), new DateTime()]
                )
                ->get();

            foreach ($playbacks as $playback) {
                if (array_key_exists($playback->track->uri, $tracks)) {
                    $tracks[$playback->track->uri]['count']++;
                } else {
                    $tracks[$playback->track->uri] = [
                        'track' => $playback->track,
                        'count' => 1,
                    ];
                }
            }

            uasort(
                $tracks,
                static function ($a, $b) {
                    return $b['count'] <=> $a['count'];
                }
            );

            $trackCount = min(count($tracks), self::MAX_SONG_COUNT);

            $tracks = array_slice($tracks, 0, $trackCount);

            $trackUris = array_map(
                static function ($track) {
                    return $track['track']->uri;
                },
                $tracks
            );

            $response = $this->spotifyApi->execute(
                (new AddTrackToPlaylistRequest($playlist->id))->setUser($user),
                $trackUris
            );
        }
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

    private function getPlaylistName(): string
    {
        return sprintf(
            'Weekly Top %d (%s)',
            self::MAX_SONG_COUNT,
            (new DateTime())->format('Y-m-d')
        );
    }

    private function getPlaylistDescription(): string
    {
        return sprintf(
            'Top played tracks between %s and %s',
            (new DateTime('-1 week'))->format('Y-m-d'),
            (new DateTime())->format('Y-m-d')
        );

    }
}
