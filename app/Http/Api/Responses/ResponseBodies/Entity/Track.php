<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

use Illuminate\Database\Eloquent\Collection;

class Track implements EntityInterface
{
    private ?Album $album = null;

    private Collection $artists;

    private ?array $availableMarkets = null;

    private ?int $discNumber = null;

    private ?int $durationMs = null;

    private ?bool $explicit = null;

    private ?ExternalId $externalId;

    private ?ExternalUrl $externalUrl;

    private ?string $href = null;

    private ?string $id = null;

    private ?bool $isLocal = null;

    private ?bool $isPlayable = null;

    private ?Track $linkedFrom = null;

    private ?string $name = null;

    private ?int $popularity = null;

    private ?string $previewUrl = null;

    private ?TrackRestriction $restrictions = null;

    private ?int $trackNumber = null;

    private ?string $type = null;

    private ?string $uri = null;

    public function __construct()
    {
        $this->artists = new Collection();
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): void
    {
        $this->album = $album;
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

    public function getExplicit(): ?bool
    {
        return $this->explicit;
    }

    public function setExplicit(?bool $explicit): void
    {
        $this->explicit = $explicit;
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

    public function getIsLocal(): ?bool
    {
        return $this->isLocal;
    }

    public function setIsLocal(?bool $isLocal): void
    {
        $this->isLocal = $isLocal;
    }


    public function getIsPlayable(): ?bool
    {
        return $this->isPlayable;
    }

    public function setIsPlayable(?bool $isPlayable): void
    {
        $this->isPlayable = $isPlayable;
    }


    public function getLinkedFrom(): ?Track
    {
        return $this->linkedFrom;
    }

    public function setLinkedFrom(?Track $linkedFrom): void
    {
        $this->linkedFrom = $linkedFrom;
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

    public function getRestrictions(): ?TrackRestriction
    {
        return $this->restrictions;
    }

    public function setRestrictions(?TrackRestriction $restrictions): void
    {
        $this->restrictions = $restrictions;
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

    public function toArray(): array
    {
        return [
            'album' => isset($this->album) ? $this->album->toArray() : null,
            'artists' => $this->artists->map(fn(Artist $artist) => $artist->toArray())->toArray(),
            'availableMarkets' => $this->availableMarkets,
            'discNumber' => $this->discNumber,
            'durationMs' => $this->durationMs,
            'explicit' => $this->explicit,
            'externalId' => isset($this->externalId) ? $this->externalId->toArray() : null,
            'externalUrl' => isset($this->externalUrl) ? $this->externalUrl->toArray() : null,
            'href' => $this->href,
            'id' => $this->id,
            'isLocal' => $this->isLocal,
            'isPlayable' => $this->isPlayable,
            'linkedFrom' => $this->linkedFrom,
            'name' => $this->name,
            'popularity' => $this->popularity,
            'previewUrl' => $this->previewUrl,
            'restrictions' => isset($this->restrictions) ? $this->restrictions->toArray() : null,
            'trackNumber' => $this->trackNumber,
            'type' => $this->type,
            'uri' => $this->uri,
        ];
    }
}
