<?php

declare(strict_types=1);

namespace App\Entities\Spotify;

use App\Entities\Traits\SpotifyResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\UserInterface;

interface ProfileInterface extends SpotifyResourceInterface, TimestampableInterface
{
    public function getCountry(): ?string;

    public function setCountry(?string $country): void;

    public function getDisplayName(): ?string;

    public function setDisplayName(?string $displayName): void;

    public function getExplicitContent(): ?array;

    public function setExplicitContent(?array $explicitContent): void;

    public function getExternalUrl(): ?array;

    public function setExternalUrl(?array $externalUrl): void;

    public function getFollowers(): ?array;

    public function setFollowers(?array $followers): void;

    public function getHref(): ?string;

    public function setHref(?string $href): void;

    public function getImages(): ?array;

    public function setImages(?array $images): void;

    public function getProduct(): ?string;

    public function setProduct(?string $product): void;

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getUri(): ?string;

    public function setUri(?string $uri): void;

    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user): void;
}
