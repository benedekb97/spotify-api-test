<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\UserInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

interface PlaylistInterface
{
    public function isCollaborative(): ?bool;

    public function setCollaborative(?bool $collaborative): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getExternalUrl(): ?array;

    public function setExternalUrl(?array $externalUrl): void;

    public function getFollowers(): ?array;

    public function setFollowers(?array $followers): void;

    public function getHref(): ?string;

    public function setHref(?string $href): void;

    public function getImages(): ?array;

    public function setImages(?array $images): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getOwner(): ?UserInterface;

    public function setOwner(?UserInterface $owner): void;

    public function getOwnerUserId(): ?string;

    public function setOwnerUserId(?string $ownerUserId): void;

    public function getSnapshotId(): ?string;

    public function setSnapshotId(?string $snapshotId): void;

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getUri(): ?string;

    public function setUri(?string $uri): void;

    public function getPlaylistTracks(): Collection;

    public function hasPlaylistTrack(PlaylistTrackInterface $playlistTrack): bool;

    public function addPlaylistTrack(PlaylistTrackInterface $playlistTrack): void;

    public function removePlaylistTrack(PlaylistTrackInterface $playlistTrack): void;

    public function setLocalUser(?UserInterface $user): void;

    public function getLocalUser(): ?UserInterface;

    public function hasLocalUser(): bool;

    public function isViewableByUser(?UserInterface $user): bool;

    public function isTopPlayed(): bool;

    public function setTopPlayed(?bool $topPlayed): void;

    public function getTrackAssociations(): Collection;

    public function hasTrackAssociation(TrackAssociationInterface $trackAssociation): bool;

    public function addTrackAssociation(TrackAssociationInterface $trackAssociation): void;

    public function removeTrackAssociation(TrackAssociationInterface $trackAssociation): void;

    public function getRecommendedTracks(): array;

    public function hasTrack(TrackInterface $track): bool;
}
