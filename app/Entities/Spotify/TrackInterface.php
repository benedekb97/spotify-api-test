<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

interface TrackInterface
{
    public const TYPE_TRACK = 'track';

    public function setId(string $id): void;

    public function getId(): ?string;

    public function getAvailableMarkets(): ?array;

    public function setAvailableMarkets(?array $availableMarkets): void;

    public function getDiscNumber(): ?int;

    public function setDiscNumber(?int $discNumber): void;

    public function getDurationMs(): ?int;

    public function setDurationMs(?int $durationMs): void;

    public function isExplicit(): bool;

    public function setExplicit(bool $explicit): void;

    public function getExternalIds(): ?array;

    public function setExternalIds(?array $externalIds): void;

    public function getExternalUrls(): ?array;

    public function setExternalUrls(?array $externalUrls): void;

    public function getHref(): ?string;

    public function setHref(?string $href): void;

    public function isLocal(): ?bool;

    public function setLocal(?bool $local): void;

    public function isPlayable(): ?bool;

    public function setPlayable(?bool $playable): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getPopularity(): ?int;

    public function setPopularity(?int $popularity): void;

    public function getPreviewUrl(): ?string;

    public function setPreviewUrl(?string $previewUrl): void;

    public function getTrackNumber(): ?int;

    public function setTrackNumber(?int $trackNumber): void;

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getUri(): ?string;

    public function setUri(?string $uri): void;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(?DateTimeInterface $createdAt): void;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void;

    public function getAlbum(): ?AlbumInterface;

    public function setAlbum(?AlbumInterface $album): void;

    public function getArtists(): Collection;

    public function hasArtist(ArtistInterface $artist): bool;

    public function addArtist(ArtistInterface $artist): void;

    public function removeArtist(ArtistInterface $artist): void;
}
