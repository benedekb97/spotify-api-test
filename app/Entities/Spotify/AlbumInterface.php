<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\SpotifyResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

interface AlbumInterface extends SpotifyResourceInterface, TimestampableInterface
{
    public const TYPE_ALBUM = 'album';

    public function getAvailableMarkets(): ?array;

    public function setAvailableMarkets(?array $availableMarkets): void;

    public function getCopyrights(): ?array;

    public function setCopyrights(?array $copyrights): void;

    public function getExternalIds(): ?array;

    public function setExternalIds(?array $externalIds): void;

    public function getExternalUrls(): ?array;

    public function setExternalUrls(?array $externalUrls): void;

    public function getGenres(): ?array;

    public function setGenres(?array $genres): void;

    public function getHref(): ?string;

    public function setHref(?string $href): void;

    public function getImages(): ?array;

    public function setImages(?array $images): void;

    public function getLabel(): ?string;

    public function setLabel(?string $label): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getPopularity(): ?int;

    public function setPopularity(?int $popularity): void;

    public function getReleaseDate(): ?string;

    public function setReleaseDate(?string $releaseDate): void;

    public function getReleaseDatePrecision(): ?string;

    public function setReleaseDatePrecision(?string $releaseDatePrecision): void;

    public function getRestrictions(): ?array;

    public function setRestrictions(?array $restrictions): void;

    public function getTotalTracks(): ?int;

    public function setTotalTracks(?int $totalTracks): void;

    public function getType(): string;

    public function setType(string $type): void;

    public function getUri(): ?string;

    public function setUri(?string $uri): void;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(?DateTimeInterface $createdAt): void;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void;

    public function getArtists(): Collection;

    public function hasArtist(ArtistInterface $artist): bool;

    public function addArtist(ArtistInterface $artist): void;

    public function removeArtist(ArtistInterface $artist): void;

    public function getTracks(): Collection;

    public function getSortedTracks(): Collection;

    public function hasTrack(TrackInterface $track): bool;

    public function addTrack(TrackInterface $track): void;

    public function removeTrack(TrackInterface $track): void;
}
