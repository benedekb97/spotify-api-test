<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Http\Api\Events\CreatePlaybackEvent;
use App\Models\Spotify\Playback;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;

class CreatePlaybackListener
{
    private const DURATION_OFFSET = 60 * 2;

    public function handle(CreatePlaybackEvent $event): void
    {
        $track = $event->getTrack();

        if ($track === null) {
            return;
        }

        if (!$this->hasRecentPlayback($track->getId(), (int)($track->getDurationMs() / 1000) + self::DURATION_OFFSET)) {
            $playback = new Playback();

            $playback->user_id = Auth::id();
            $playback->track_id = $track->getId();

            $playback->save();
        }
    }

    private function hasRecentPlayback(string $trackId, int $duration): bool
    {
        $playback = Playback::where('user_id', Auth::id())
            ->where('track_id', $trackId)
            ->where(
                'created_at',
                '>',
                (new DateTime())->sub(new DateInterval("PT{$duration}S"))
            )
            ->first();

        if ($playback !== null) {
            return true;
        }

        return false;
    }
}
