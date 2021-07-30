<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

use Illuminate\Database\Eloquent\Collection;

class Album
{
    private ?string $albumType = null;

    private Collection $artists;

    private ?array $availableMarkets = null;

    private Collection $copyrights;

    private ?ExternalId $externalId = null;

    private ?ExternalUrl $externalUrl = null;

    private ?array $genres = null;

    private ?string $href = null;

    private ?string $id = null;

    private Collection $images;

    private ?string $label = null;

    private ?string $name = null;

    private ?int $popularity = null;

    private ?string $releaseDate = null;

    private ?string $releaseDatePrecision = null;

    private ?AlbumRestriction $albumRestriction = null;

    private ?int $totalTracks = null;

    private Collection $tracks;

    private ?string $type = null;

    private ?string $uri = null;

    public function __construct()
    {
        $this->artists = new Collection();
        $this->copyrights = new Collection();
        $this->images = new Collection();
        $this->tracks = new Collection();
    }

    public function getAlbumType(): ?string
    {
        return $this->albumType;
    }

    public function setAlbumType(?string $albumType): void
    {
        $this->albumType = $albumType;
    }

    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): void
    {
        $this->artists->add($artist);
    }

    public function getAvailableMarkets(): ?array
    {
        return $this->availableMarkets;
    }

    public function setAvailableMarkets(?array $availableMarkets): void
    {
        $this->availableMarkets = $availableMarkets;
    }

    public function getCopyrights(): Collection
    {
        return $this->copyrights;
    }

    public function addCopyright(Copyright $copyright): void
    {
        $this->copyrights->add($copyright);
    }

    public function getExternalId(): ?ExternalId
    {
        return $this->externalId;
    }

    public function setExternalId(?ExternalId $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getExternalUrl(): ?ExternalUrl
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?ExternalUrl $externalUrl): void
    {
        $this->externalUrl = $externalUrl;
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

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): void
    {
        $this->images->add($image);
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
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

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?string $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getReleaseDatePrecision(): ?string
    {
        return $this->releaseDatePrecision;
    }

    public function setReleaseDatePrecision(?string $releaseDatePrecision): void
    {
        $this->releaseDatePrecision = $releaseDatePrecision;
    }

    public function getAlbumRestriction(): ?AlbumRestriction
    {
        return $this->albumRestriction;
    }

    public function setAlbumRestriction(?AlbumRestriction $albumRestriction): void
    {
        $this->albumRestriction = $albumRestriction;
    }

    public function getTotalTracks(): ?int
    {
        return $this->totalTracks;
    }

    public function setTotalTracks(?int $totalTracks): void
    {
        $this->totalTracks = $totalTracks;
    }

    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTracks(SimplifiedTrack $track): void
    {
        $this->tracks->add($track);
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

    public function toArray(): array
    {
        return [
            'albumType' => $this->albumType,
            'artists' => $this->artists->map(static fn (Artist $artist) => $artist->toArray())->toArray(),
            'availableMarkets' => $this->availableMarkets,
            'copyrights' => $this->copyrights->map(fn (Copyright $copyright) => $copyright->toArray()),
            'externalId' => isset($this->externalId) ? $this->externalId->toArray() : null,
            'externalUrl' => isset($this->externalUrl) ? $this->externalUrl->toArray() : null,
            'genres' => $this->genres,
            'href' => $this->href,
            'id' => $this->id,
            'images' => $this->images->map(static fn (Image $image) => $image->toArray())->toArray(),
            'label' => $this->label,
            'name' => $this->name,
            'popularity' => $this->popularity,
            'releaseDate' => $this->releaseDate,
            'releaseDatePrecision' => $this->releaseDatePrecision,
            'albumRestriction' => isset($this->albumRestriction) ? $this->albumRestriction->toArray() : null,
            'totalTracks' => $this->totalTracks,
            'tracks' => $this->tracks->map(static fn (SimplifiedTrack $t) => $t->toArray())->toArray(),
            'type' => $this->type,
            'uri' => $this->uri,
        ];
    }
}
