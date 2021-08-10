<?php

declare(strict_types=1);

namespace App\Http\Api\Factories\ResponseBodies\Entity;

use App\Http\Api\Responses\ResponseBodies\Entity\Playlist;

class PlaylistFactory
{
    private FollowersFactory $followersFactory;

    private ExternalUrlFactory $externalUrlFactory;

    private ImageFactory $imageFactory;

    private PublicUserFactory $publicUserFactory;

    private PlaylistTrackFactory $playlistTrackFactory;

    public function __construct(
        FollowersFactory $followersFactory,
        ExternalUrlFactory $externalUrlFactory,
        ImageFactory $imageFactory,
        PublicUserFactory $publicUserFactory,
        PlaylistTrackFactory $playlistTrackFactory
    ) {
        $this->followersFactory = $followersFactory;
        $this->externalUrlFactory = $externalUrlFactory;
        $this->imageFactory = $imageFactory;
        $this->publicUserFactory = $publicUserFactory;
        $this->playlistTrackFactory = $playlistTrackFactory;
    }

    /**
     * @param array $data
     * @return Playlist
     */
    public function create(array $data): Playlist
    {
        $playlist = new Playlist();

        if (isset($data['followers'])) {
            $playlist->setFollowers(
                $this->followersFactory->create($data['followers'])
            );
        }

        if (isset($data['external_urls'])) {
            $playlist->setExternalUrl(
                $this->externalUrlFactory->create($data['external_urls'])
            );
        }

        $playlist->setDescription($data['description']);
        $playlist->setCollaborative($data['collaborative']);
        $playlist->setPublic($data['public']);
        $playlist->setName($data['name']);
        $playlist->setId($data['id']);
        $playlist->setHref($data['href']);

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $playlist->addImage(
                    $this->imageFactory->create($image)
                );
            }
        }

        if (isset($data['tracks'])) {
            $playlist->setTracksData($data['tracks']);
        }

        $playlist->setOwner(
            $this->publicUserFactory->create($data['owner'])
        );

        $playlist->setSnapshotId($data['snapshot_id']);

        if (isset($data['items'])) {
            foreach ($data['items'] as $track) {
                $playlist->addTrack(
                    $this->playlistTrackFactory->create($track)
                );
            }
        }

        $playlist->setType($data['type']);
        $playlist->setUri($data['uri']);

        return $playlist;
    }
}
