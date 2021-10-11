<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\ProfileInterface;
use App\Entities\Spotify\TrackInterface;
use App\Entities\Spotify\UserTrackInterface;
use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Cache\Persister\Collection\CachedCollectionPersister;

class User implements UserInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    private ?string $name = null;

    private ?string $email = null;

    private ?string $password = null;

    private ?string $spotifyAccessToken = null;

    private ?string $spotifyRefreshToken = null;

    private ?DateTimeInterface $spotifyAccessTokenExpiry = null;

    private ?string $spotifyId = null;

    private Collection $scopes;

    private Collection $playlists;

    private bool $automaticallyCreateWeeklyPlaylist = false;

    private Collection $userTracks;

    private ?ProfileInterface $profile = null;

    private ?DateTimeInterface $playbacksUpdatedAt = null;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->scopes = new ArrayCollection();
        $this->userTracks = new ArrayCollection();
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
        return (new DateTime()) > $this->getSpotifyAccessTokenExpiry();
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

    public function getUserTracks(): Collection
    {
        return $this->userTracks;
    }

    public function hasUserTrack(UserTrackInterface $userTrack): bool
    {
        return $this->userTracks->contains($userTrack);
    }

    public function addUserTrack(UserTrackInterface $userTrack): void
    {
        if (!$this->hasUserTrack($userTrack)) {
            $this->userTracks->add($userTrack);
            $userTrack->setUser($this);
        }
    }

    public function removeUserTrack(UserTrackInterface $userTrack): void
    {
        if ($this->hasUserTrack($userTrack)) {
            $this->userTracks->removeElement($userTrack);
            $userTrack->setUser(null);
        }
    }

    public function getTracks(): Collection
    {
        return $this->userTracks->map(
            static function (UserTrackInterface $userTrack): ?TrackInterface
            {
                return $userTrack->getTrack();
            }
        );
    }

    public function hasTrack(TrackInterface $track): bool
    {
        return $this->getTracks()->contains($track);
    }

    public function getProfile(): ?ProfileInterface
    {
        return $this->profile;
    }

    public function setProfile(?ProfileInterface $profile): void
    {
        $this->profile = $profile;
    }

    public function hasProfile(): bool
    {
        return isset($this->profile);
    }

    public function getPlaybacksUpdatedAt(): ?DateTimeInterface
    {
        return $this->playbacksUpdatedAt;
    }

    public function setPlaybacksUpdatedAtNow(): void
    {
        $this->playbacksUpdatedAt = new DateTime();
    }
}
