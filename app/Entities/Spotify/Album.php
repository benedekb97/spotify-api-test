<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\SpotifyResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Album implements AlbumInterface
{
    use SpotifyResourceTrait;
    use TimestampableTrait;

    private ?array $availableMarkets = null;

    private ?array $copyrights = null;

    private ?array $externalIds = null;

    private ?array $externalUrls = null;

    private ?array $genres = null;

    private ?string $href = null;

    private ?array $images = null;

    private ?string $label = null;

    private ?string $name = null;

    private ?int $popularity = null;

    private ?string $releaseDate = null;

    private ?string $releaseDatePrecision = null;

    private ?array $restrictions = null;

    private ?int $totalTracks = null;

    private string $type = self::TYPE_ALBUM;

    private ?string $uri = null;

    private Collection $artists;

    private Collection $tracks;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->tracks = new ArrayCollection();
    }

    public function getAvailableMarkets(): ?array
    {
        return $this->availableMarkets;
    }

    public function setAvailableMarkets(?array $availableMarkets): void
    {
        $this->availableMarkets = $availableMarkets;
    }

    public function getCopyrights(): ?array
    {
        return $this->copyrights;
    }

    public function setCopyrights(?array $copyrights): void
    {
        $this->copyrights = $copyrights;
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

    public function getRestrictions(): ?array
    {
        return $this->restrictions;
    }

    public function setRestrictions(?array $restrictions): void
    {
        $this->restrictions = $restrictions;
    }

    public function getTotalTracks(): ?int
    {
        return $this->totalTracks;
    }

    public function setTotalTracks(?int $totalTracks): void
    {
        $this->totalTracks = $totalTracks;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
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
            $artist->addAlbum($this);
        }
    }

    public function removeArtist(ArtistInterface $artist): void
    {
        if ($this->hasArtist($artist)) {
            $this->artists->removeElement($artist);
            $artist->removeAlbum($this);
        }
    }

    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function hasTrack(TrackInterface $track): bool
    {
        return $this->tracks->contains($track);
    }

    public function addTrack(TrackInterface $track): void
    {
        if (!$this->hasTrack($track)) {
            $this->tracks->add($track);
            $track->setAlbum($this);
        }
    }

    public function removeTrack(TrackInterface $track): void
    {
        if ($this->hasTrack($track)) {
            $this->tracks->removeElement($track);
            $track->setAlbum(null);
        }
    }
}
