<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\ArtistInterface;
use App\Entities\Spotify\TrackInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ArtistTracksProvider implements ArtistTracksProviderInterface
{
    private UserProviderInterface $userProvider;

    public function __construct(
        UserProvider $userProvider
    ) {
        $this->userProvider = $userProvider;
    }

    public function provide(ArtistInterface $artist): Collection
    {
        $tracks = $artist->getTracks();

        $this->sortByPlaybackCount($tracks);

        return $tracks;
    }

    private function sortByPlaybackCount(Collection & $tracks): void
    {
        $iterator = $tracks->getIterator();

        $iterator->uasort(
            function (TrackInterface $a, TrackInterface $b): int
            {
                $user = $this->userProvider->provide();

                return $b->getPlaybackCountByUser($user) <=> $a->getPlaybackCountByUser($user);
            }
        );

        $tracks = new ArrayCollection(iterator_to_array($iterator));
    }
}
