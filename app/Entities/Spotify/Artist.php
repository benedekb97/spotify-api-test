<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Artist implements ArtistInterface
{
    public const TYPE_ARTIST = 'artist';

    private ?string $id = null;

    private ?array $followers = null;

    private ?array $genres = null;

    private ?string $href = null;

    private ?array $images = null;

    private ?string $name = null;

    private ?int $popularity = null;

    private ?string $type = self::TYPE_ARTIST;

    private ?string $uri = null;

    private ?DateTimeInterface $createdAt = null;

    private ?DateTimeInterface $updatedAt = null;

    private Collection $albums;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFollowers(): ?array
    {
        return $this->followers;
    }

    public function setFollowers(?array $followers): void
    {
        $this->followers = $followers;
    }

    public function getGenres(): ?array
    {
        return $this->genres;
    }

    public function setGenres(?array $genres): void
    {
        $this->genres = $genres;
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

    public function getPopularity(): ?int
    {
        return $this->popularity;
    }

    public function setPopularity(?int $popularity): void
    {
        $this->popularity = $popularity;
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

    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function hasAlbum(AlbumInterface $album): bool
    {
        return $this->albums->contains($album);
    }

    public function addAlbum(AlbumInterface $album): void
    {
        if (!$this->hasAlbum($album)) {
            $this->albums->add($album);
            $album->addArtist($this);
        }
    }

    public function removeAlbum(AlbumInterface $album): void
    {
        if ($this->hasAlbum($album)) {
            $this->albums->removeElement($album);
            $album->removeArtist($this);
        }
    }
}
