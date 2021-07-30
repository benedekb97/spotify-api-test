<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

use Illuminate\Database\Eloquent\Collection;

class Artist
{
    private ?ExternalUrl $externalUrl = null;

    private ?Followers $followers = null;

    private ?array $genres = null;

    private ?string $href = null;

    private ?string $id = null;

    private Collection $images;

    private ?string $name = null;

    private ?int $popularity = null;

    private ?string $type = null;

    private ?string $uri = null;

    public function __construct()
    {
        $this->images = new Collection();
    }

    public function getExternalUrl(): ?ExternalUrl
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(ExternalUrl $externalUrl): void
    {
        $this->externalUrl = $externalUrl;
    }

    public function getFollowers(): ?Followers
    {
        return $this->followers;
    }

    public function setFollowers(?Followers $followers): void
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

    public function toArray(): array
    {
        return [
            'externalUrl' => isset($this->externalUrl) ? $this->externalUrl->toArray() : null,
            'followers' => isset($this->followers) ? $this->followers->toArray() : null,
            'genres' => $this->genres,
            'href' => $this->href,
            'id' => $this->id,
            'images' => $this->images->map(static fn (Image $i) => $i->toArray())->toArray(),
            'name' => $this->name,
            'popularity' => $this->popularity,
            'type' => $this->type,
            'uri' => $this->uri,
        ];
    }
}
