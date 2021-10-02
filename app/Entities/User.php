<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Spotify\PlaylistInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User implements UserInterface
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $email = null;

    private ?string $password = null;

    private ?DateTimeInterface $createdAt = null;

    private ?DateTimeInterface $updatedAt = null;

    private ?string $spotifyAccessToken = null;

    private ?string $spotifyRefreshToken = null;

    private ?DateTimeInterface $spotifyAccessTokenExpiry = null;

    private ?string $spotifyId = null;

    private Collection $scopes;

    private Collection $playlists;

    private bool $automaticallyCreateWeeklyPlaylist = false;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->scopes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
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

    public function getSpotifyAccessToken(): ?string
    {
        return $this->spotifyAccessToken;
    }

    public function setSpotifyAccessToken(?string $spotifyAccessToken): void
    {
        $this->spotifyAccessToken = $spotifyAccessToken;
    }

    public function getSpotifyRefreshToken(): ?string
    {
        return $this->spotifyRefreshToken;
    }

    public function setSpotifyRefreshToken(?string $spotifyRefreshToken): void
    {
        $this->spotifyRefreshToken = $spotifyRefreshToken;
    }

    public function getSpotifyAccessTokenExpiry(): ?DateTimeInterface
    {
        return $this->spotifyAccessTokenExpiry;
    }

    public function setSpotifyAccessTokenExpiry(?DateTimeInterface $spotifyAccessTokenExpiry): void
    {
        $this->spotifyAccessTokenExpiry = $spotifyAccessTokenExpiry;
    }

    public function getSpotifyId(): ?string
    {
        return $this->spotifyId;
    }

    public function setSpotifyId(?string $spotifyId): void
    {
        $this->spotifyId = $spotifyId;
    }

    public function getScopes(): Collection
    {
        return $this->scopes;
    }

    public function hasScope(ScopeInterface $scope): bool
    {
        return $this->scopes->contains($scope);
    }

    public function addScope(ScopeInterface $scope): void
    {
        if (!$this->hasScope($scope)) {
            $this->scopes->add($scope);
        }
    }

    public function removeScope(ScopeInterface $scope): void
    {
        if ($this->hasScope($scope)) {
            $this->scopes->remove($scope);
        }
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): int
    {
        return $this->id;
    }

    public function getAuthPassword(): ?string
    {
        return null;
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        throw new \LogicException('What are you do.');
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }

    public function isLoggedInWithSpotify(): bool
    {
        return $this->getSpotifyAccessToken() !== null
            && $this->getSpotifyRefreshToken() !== null
            && $this->getSpotifyAccessTokenExpiry() !== null
            && (new DateTime()) < $this->getSpotifyAccessTokenExpiry();
    }

    public function needsReauthentication(): bool
    {
        return $this->isLoggedInWithSpotify() && (new DateTime()) > $this->getSpotifyAccessTokenExpiry();
    }

    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function hasPlaylist(PlaylistInterface $playlist): bool
    {
        return $this->playlists->contains($playlist);
    }

    public function addPlaylist(PlaylistInterface $playlist): void
    {
        if (!$this->hasPlaylist($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setLocalUser($this);
        }
    }

    public function removePlaylist(PlaylistInterface $playlist): void
    {
        if ($this->hasPlaylist($playlist)) {
            $this->playlists->removeElement($playlist);
            $playlist->setLocalUser(null);
        }
    }

    public function automaticallyCreateWeeklyPlaylist(): bool
    {
        return $this->automaticallyCreateWeeklyPlaylist;
    }

    public function setAutomaticallyCreateWeeklyPlaylist(bool $automaticallyCreateWeeklyPlaylist): void
    {
        $this->automaticallyCreateWeeklyPlaylist = $automaticallyCreateWeeklyPlaylist;
    }
}
