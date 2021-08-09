<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

use Illuminate\Database\Eloquent\Collection;

class PublicUser implements EntityInterface
{
    private ?string $displayName = null;

    private ?ExternalUrl $externalUrl = null;

    private ?Followers $followers = null;

    private ?string $href = null;

    private ?string $id = null;

    /** @var Collection|Image[] $images */
    private Collection $images;

    private ?string $type = null;

    private ?string $uri = null;

    public function __construct()
    {
        $this->images = new Collection();
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function getExternalUrl(): ?ExternalUrl
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?ExternalUrl $externalUrl): void
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

    public function getImages()
    {
        return $this->images;
    }

    public function addImage(Image $image): void
    {
        $this->images->add($image);
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
            'displayName' => $this->displayName,
            'externalUrl' => isset($this->externalUrl) ? $this->externalUrl->toArray() : null,
            'followers' => isset($this->followers) ? $this->followers->toArray() : null,
            'href' => $this->href,
            'id' => $this->id,
            'images' => $this->images->map(fn ($i) => $i->toArray())->toArray(),
            'type' => $this->type,
            'uri' => $this->uri,
        ];
    }
}
