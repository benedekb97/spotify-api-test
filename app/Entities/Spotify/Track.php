<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Track implements TrackInterface
{
    private ?string $id = null;

    private ?array $availableMarkets = null;

    private ?int $discNumber = null;

    private ?int $durationMs = null;

    private bool $explicit = false;

    private ?array $externalIds = null;

    private ?array $externalUrls = null;

    private ?string $href = null;

    private ?bool $local = null;

    private ?bool $playable = null;

    private ?string $name = null;

    private ?int $popularity = null;

    private ?string $previewUrl = null;

    private ?int $trackNumber = null;

    private ?string $type = self::TYPE_TRACK;

    private ?string $uri = null;

    private ?DateTimeInterface $createdAt = null;

    private ?DateTimeInterface $updatedAt = null;

    private ?AlbumInterface $album = null;

    private Collection $artists;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAvailableMarkets(): ?array
    {
        return $this->availableMarkets;
    }

    public function setAvailableMarkets(?array $availableMarkets): void
    {
        $this->availableMarkets = $availableMarkets;
    }

    public function getDiscNumber(): ?int
    {
        return $this->discNumber;
    }

    public function setDiscNumber(?int $discNumber): void
    {
        $this->discNumber = $discNumber;
    }

    public function getDurationMs(): ?int
    {
        return $this->durationMs;
    }

    public function setDurationMs(?int $durationMs): void
    {
        $this->durationMs = $durationMs;
    }

    public function isExplicit(): bool
    {
        return $this->explicit;
    }

    public function setExplicit(bool $explicit): void
    {
        $this->explicit = $explicit;
    }

    public function getExternalIds(): ?array
    {
        return $this->externalIds;
    }

    public function setExternalIds(?array $externalIds): void
    {
        $this->externalIds = $externalIds;
    }

    public function getExternalUrls(): ?array
    {
        return $this->externalUrls;
    }

    public function setExternalUrls(?array $externalUrls): void
    {
        $this->externalUrls = $externalUrls;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): void
    {
        $this->href = $href;
    }

    public function isLocal(): ?bool
    {
        return $this->local;
    }

    public function setLocal(?bool $local): void
    {
        $this->local = $local;
    }

    public function isPlayable(): ?bool
    {
        return $this->playable;
    }

    public function setPlayable(?bool $playable): void
    {
        $this->playable = $playable;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPopularity(): ?int
    {
        return $this->popularity;
    }

    public function setPopularity(?int $popularity): void
    {
        $this->popularity = $popularity;
    }

    public function getPreviewUrl(): ?string
    {
        return $this->previewUrl;
    }

    public function setPreviewUrl(?string $previewUrl): void
    {
        $this->previewUrl = $previewUrl;
    }

    public function getTrackNumber(): ?int
    {
        return $this->trackNumber;
    }

    public function setTrackNumber(?int $trackNumber): void
    {
        $this->trackNumber = $trackNumber;
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

    public function getAlbum(): ?AlbumInterface
    {
        return $this->album;
    }

    public function setAlbum(?AlbumInterface $album): void
    {
        $this->album = $album;
    }

    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function hasArtist(ArtistInterface $artist): bool
    {
        return $this->artists->contains($artist);
    }

    public function addArtist(ArtistInterface $artist): void
    {
        if (!$this->hasArtist($artist)) {
            $this->artists->add($artist);
        }
    }

    public function removeArtist(ArtistInterface $artist): void
    {
        if ($this->hasArtist($artist)) {
            $this->artists->removeElement($artist);
        }
    }
}
