<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\SpotifyResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use Doctrine\Common\Collections\Collection;

interface ArtistInterface extends SpotifyResourceInterface, TimestampableInterface
{
    public function getFollowers(): ?array;

    public function setFollowers(?array $followers): void;

    public function getGenres(): ?array;

    public function setGenres(?array $genres): void;

    public function getHref(): ?string;

    public function setHref(?string $href): void;

    public function getImages(): ?array;

    public function setImages(?array $images): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getPopularity(): ?int;

    public function setPopularity(?int $popularity): void;

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getUri(): ?string;

    public function setUri(?string $uri): void;

    public function getAlbums(): Collection;

    public function hasAlbum(AlbumInterface $album): bool;

    public function addAlbum(AlbumInterface $album): void;

    public function removeAlbum(AlbumInterface $album): void;

    public function getTracks(): Collection;

    public function hasTrack(TrackInterface $track): bool;

    public function addTrack(TrackInterface $track): void;

    public function removeTrack(TrackInterface $track): void;
}
