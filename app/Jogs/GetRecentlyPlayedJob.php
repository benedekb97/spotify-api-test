<?php

declare(strict_types=1);

namespace App\Jogs;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Api\Requests\GetRecentlyPlayedTracksRequest;
use App\Http\Api\Responses\ResponseBodies\Entity\RecentlyPlayed;
use App\Http\Api\Responses\ResponseBodies\RecentlyPlayedTracksResponseBody;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Models\Spotify\Playback;
use App\Models\User;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Facades\Log;

class GetRecentlyPlayedJob
{
    private const EPSILON = 3 * 60;

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
            static function (User $user): bool
            {
                return $user->isLoggedInWithSpotify();
            }
        );

        $before = time();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->needsReauthentication()) {
                $response = $this->spotifyAuthenticationApi->refreshAccessToken($user->spotify_refresh_token);

                if ($response === null) {
                    continue;
                }

                $tokenExpiry = (new DateTime())
                    ->add(new DateInterval(sprintf('PT%sS', $response->getExpiresIn())));

                $user->spotify_access_token = $response->getAccessToken();
                $user->spotify_access_token_expiry = $tokenExpiry->format('Y-m-d H:i:s');
                $user->save();
            }

            $request = new GetRecentlyPlayedTracksRequest(30, null, $before);

            $request->setUser($user);

            Log::info('[RECENTLY_PLAYED] Getting recently played songs for user ' . $user->id . ' - ' . $user->name);

            $recentlyPlayed = $this->spotifyApi->execute($request);

            if ($recentlyPlayed === null) {
                Log::error('[RECENTLY_PLAYED] No response. Skipping.');

                continue;
            }

            /** @var RecentlyPlayedTracksResponseBody|null $body */
            $body = $recentlyPlayed->getBody();

            if (!$body instanceof RecentlyPlayedTracksResponseBody) {
                Log::error('[RECENTLY_PLAYED] No body to response. Skipping.');

                continue;
            }

            Log::info('[RECENTLY_PLAYED] Processing ' . count($body->getItems()) . ' tracks for user ' . $user->id . ' - ' . $user->name);

            /** @var RecentlyPlayed $recentlyPlayed */
            foreach ($body->getItems() as $recentlyPlayed) {
                if (
                    !$this->hasEntry(
                        $recentlyPlayed->getTrack()->getId(),
                        $user->id,
                        $recentlyPlayed->getPlayedAt()
                    )
                ) {
                    $playback = new Playback();

                    $playback->user_id = $user->id;
                    $playback->track_id = $recentlyPlayed->getTrack()->getId();
                    $playback->played_at = $recentlyPlayed->getPlayedAt()->format('Y-m-d H:i:s');

                    $playback->save();
                }
            }
        }
    }

    private function hasEntry(string $trackId, int $userId, DateTimeInterface $playedAt): bool
    {
        $start = $playedAt->sub(new DateInterval(sprintf('PT%dS', self::EPSILON)));
        $end = $playedAt->add(new DateInterval(sprintf('PT%dS', self::EPSILON)));

        $playback = Playback::where('track_id', $trackId)
            ->where('user_id', $userId)
            ->whereBetween('played_at', [$start, $end])
            ->first();

        return $playback !== null;
    }
}
