<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\UserInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Illuminate\Support\Arr;
use function Symfony\Component\String\b;

class Playlist implements PlaylistInterface
{
    private ?string $id = null;

    private ?bool $collaborative = null;

    private ?string $description = null;

    private ?array $externalUrl = null;

    private ?array $followers = null;

    private ?string $href = null;

    private ?array $images = null;

    private ?string $name = null;

    private ?UserInterface $owner = null;

    private ?string $ownerUserId = null;

    private ?string $snapshotId = null;

    private ?string $type = null;

    private ?string $uri = null;

    private ?DateTimeInterface $createdAt = null;

    private ?DateTimeInterface $updatedAt = null;

    private Collection $playlistTracks;

    private ?UserInterface $localUser = null;

    private ?bool $topPlayed = false;

    private Collection $trackAssociations;

    public function __construct()
    {
        $this->playlistTracks = new ArrayCollection();
        $this->trackAssociations = new ArrayCollection();
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function isCollaborative(): ?bool
    {
        return $this->collaborative;
    }

    public function setCollaborative(?bool $collaborative): void
    {
        $this->collaborative = $collaborative;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getExternalUrl(): ?array
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?array $externalUrl): void
    {
        $this->externalUrl = $externalUrl;
    }

    public function getFollowers(): ?array
    {
        return $this->followers;
    }

    public function setFollowers(?array $followers): void
    {
        $this->followers = $followers;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): void
    {
        $this->href = $href;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): void
    {
        $this->images = $images;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getOwner(): ?UserInterface
    {
        return $this->owner;
    }

    public function setOwner(?UserInterface $owner): void
    {
        $this->owner = $owner;
    }

    public function getOwnerUserId(): ?string
    {
        return $this->ownerUserId;
    }

    public function setOwnerUserId(?string $ownerUserId): void
    {
        $this->ownerUserId = $ownerUserId;
    }

    public function getSnapshotId(): ?string
    {
        return $this->snapshotId;
    }

    public function setSnapshotId(?string $snapshotId): void
    {
        $this->snapshotId = $snapshotId;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): void
    {
        $this->uri = $uri;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getPlaylistTracks(): Collection
    {
        return $this->playlistTracks;
    }

    public function hasPlaylistTrack(PlaylistTrackInterface $playlistTrack): bool
    {
        return $this->playlistTracks->contains($playlistTrack);
    }

    public function addPlaylistTrack(PlaylistTrackInterface $playlistTrack): void
    {
        if (!$this->hasPlaylistTrack($playlistTrack)) {
            $this->playlistTracks->add($playlistTrack);
            $playlistTrack->setPlaylist($this);
        }
    }

    public function removePlaylistTrack(PlaylistTrackInterface $playlistTrack): void
    {
        if ($this->hasPlaylistTrack($playlistTrack)) {
            $this->playlistTracks->removeElement($playlistTrack);
            $playlistTrack->setPlaylist(null);
        }
    }

    public function setLocalUser(?UserInterface $user): void
    {
        $this->localUser = $user;
    }

    public function getLocalUser(): ?UserInterface
    {
        return $this->localUser;
    }

    public function hasLocalUser(): bool
    {
        return isset($this->localUser);
    }

    public function isViewableByUser(?UserInterface $user): bool
    {
        if ($this->isCollaborative()) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        if ($this->hasLocalUser() && $this->getLocalUser() === $user) {
            return true;
        }

        return false;
    }

    public function isTopPlayed(): bool
    {
        return $this->topPlayed ?? false;
    }

    public function setTopPlayed(?bool $topPlayed): void
    {
        $this->topPlayed = $topPlayed ?? false;
    }

    public function getTrackAssociations(): Collection
    {
        return $this->trackAssociations;
    }

    public function hasTrackAssociation(TrackAssociationInterface $trackAssociation): bool
    {
        return $this->trackAssociations->contains($trackAssociation);
    }

    public function addTrackAssociation(TrackAssociationInterface $trackAssociation): void
    {
        if (!$this->hasTrackAssociation($trackAssociation)) {
            $this->trackAssociations->add($trackAssociation);
            $trackAssociation->setPlaylist($this);
        }
    }

    public function removeTrackAssociation(TrackAssociationInterface $trackAssociation): void
    {
        if ($this->hasTrackAssociation($trackAssociation)) {
            $this->trackAssociations->removeElement($trackAssociation);
            $trackAssociation->setPlaylist(null);
        }
    }

    public function getRecommendedTracks(): array
    {
        $tracks = [];

        /** @var TrackAssociationInterface $trackAssociation */
        foreach ($this->getTrackAssociations() as $trackAssociation) {
            if (!array_key_exists($trackAssociation->getRecommendedTrack()->getId(), $tracks)) {
                $tracks[$trackAssociation->getRecommendedTrack()->getId()] = [
                    'count' => 1,
                    'track' => $trackAssociation->getRecommendedTrack(),
                ];
            } else {
                $tracks[$trackAssociation->getRecommendedTrack()->getId()]['count']++;
            }
        }

        uasort(
            $tracks,
            static function ($a, $b) {
                return $b['count'] <=> $a['count'];
            }
        );

        return $tracks;
    }

    public function hasTrack(TrackInterface $track): bool
    {
        /** @var PlaylistTrackInterface $playlistTrack */
        foreach ($this->getPlaylistTracks() as $playlistTrack) {
            if ($playlistTrack->getTrack() === $track) {
                return true;
            }
        }

        return false;
    }
}
