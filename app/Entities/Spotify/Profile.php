<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\SpotifyResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\UserInterface;

class Profile implements ProfileInterface
{
    use SpotifyResourceTrait;
    use TimestampableTrait;

    private ?string $country = null;

    private ?string $displayName = null;

    private ?array $explicitContent = null;

    private ?array $externalUrl = null;

    private ?array $followers = null;

    private ?string $href = null;

    private ?array $images = null;

    private ?string $product = null;

    private ?string $type = null;

    private ?string $uri = null;

    private ?UserInterface $user = null;

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function getExplicitContent(): ?array
    {
        return $this->explicitContent;
    }

    public function setExplicitContent(?array $explicitContent): void
    {
        $this->explicitContent = $explicitContent;
    }

    public function getExternalUrl(): ?array
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?array $externalUrl): void
    {
        $this->externalUrl = $externalUrl;
    }

    public function getFollowers(): ?array
    {
        return $this->followers;
    }

    public function setFollowers(?array $followers): void
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

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): void
    {
        $this->images = $images;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(?string $product): void
    {
        $this->product = $product;
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

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }
}
