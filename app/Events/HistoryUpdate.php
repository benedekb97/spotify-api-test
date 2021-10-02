<?php

declare(strict_types=1);

namespace App\Events;

use App\Entities\Spotify\ArtistInterface;
use App\Entities\Spotify\PlaybackInterface;
use App\Entities\UserInterface;
use Carbon\Carbon;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Collection;

class HistoryUpdate implements ShouldBroadcast
{
    private const EVENT_NAME = 'history_update';

    private UserInterface $user;

    private Collection $playbacks;

    public function __construct(
        UserInterface $user,
        Collection $playbacks
    ) {
        $this->user = $user;
        $this->playbacks = $playbacks;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel(sprintf('user.%s.history', $this->user->getId()));
    }

    public function broadcastAs(): string
    {
        return self::EVENT_NAME;
    }

    public function broadcastWith(): array
    {

        return [];

        // TODO: Fix initialization problem
        /*
        return $this->playbacks
            ->map(
                static function (PlaybackInterface $playback): array
                {
                    return [
                        'images' => $playback->getTrack()->getAlbum()->getImages()[0],
                        'title' => $playback->getTrack()->getName(),
                        'artists' => implode(
                            ', ',
                            $playback->getTrack()->getArtists()->map(fn (ArtistInterface $a) => $a->getName())->toArray()
                        ),
                        'album' => $playback->getTrack()->getAlbum()->getName(),
                        'played_at' => (new Carbon($playback->getPlayedAt()))->diffForHumans(),
                        'urls' => route('spotify.queue.add', $playback->getTrack()->getUri()),
                    ];
                }
            )
            ->toArray();
        */
    }
}
