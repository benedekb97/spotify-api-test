<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Spotify\PlaylistInterface;
use App\Http\Api\Events\UpdatePlaylistCoverEvent;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Repositories\PlaylistRepositoryInterface;

class UpdatePlaylistCoverListener
{
    private PlaylistRepositoryInterface $playlistRepository;

    public function __construct(
        PlaylistRepositoryInterface $playlistRepository
    ) {
        $this->playlistRepository = $playlistRepository;
    }

    public function handle(UpdatePlaylistCoverEvent $event): void
    {
        /** @var PlaylistInterface $playlist */
        $playlist = $this->playlistRepository->find($event->getPlaylistId());

        $playlist->setImages(
            $event->getImages()
                ->map(
                    fn (Image $i) => $i->toArray()
                )
                ->toArray()
        );

        $this->playlistRepository->add($playlist);
    }
}
