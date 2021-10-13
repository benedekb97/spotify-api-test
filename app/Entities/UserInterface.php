<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Spotify\PlaylistInterface;
use App\Entities\Spotify\ProfileInterface;
use App\Entities\Spotify\UserTrackInterface;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Illuminate\Contracts\Auth\Authenticatable;

interface UserInterface extends Authenticatable, ResourceInterface, TimestampableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getEmail(): ?string;

    public function setEmail(?string $email): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function getSpotifyAccessToken(): ?string;

    public function setSpotifyAccessToken(?string $spotifyAccessToken): void;

    public function getSpotifyRefreshToken(): ?string;

    public function setSpotifyRefreshToken(?string $spotifyRefreshToken): void;

    public function getSpotifyAccessTokenExpiry(): ?DateTimeInterface;

    public function setSpotifyAccessTokenExpiry(?DateTimeInterface $spotifyAccessTokenExpiry): void;

    public function getSpotifyId(): ?string;

    public function setSpotifyId(?string $spotifyId): void;

    public function getScopes(): Collection;

    public function hasScope(ScopeInterface $scope): bool;

    public function addScope(ScopeInterface $scope): void;

    public function removeScope(ScopeInterface $scope): void;

    public function isLoggedInWithSpotify(): bool;

    public function needsReauthentication(): bool;

    public function getPlaylists(): Collection;

    public function hasPlaylist(PlaylistInterface $playlist): bool;

    public function addPlaylist(PlaylistInterface $playlist): void;

    public function removePlaylist(PlaylistInterface $playlist): void;

    public function automaticallyCreateWeeklyPlaylist(): bool;

    public function setAutomaticallyCreateWeeklyPlaylist(bool $automaticallyCreateWeeklyPlaylist): void;

    public function getUserTracks(): Collection;

    public function hasUserTrack(UserTrackInterface $userTrack): bool;

    public function addUserTrack(UserTrackInterface $userTrack): void;

    public function removeUserTrack(UserTrackInterface $userTrack): void;

    public function getProfile(): ?ProfileInterface;

    public function setProfile(?ProfileInterface $profile): void;

    public function hasProfile(): bool;

    public function getPlaybacksUpdatedAt(): ?DateTimeInterface;

    public function setPlaybacksUpdatedAtNow(): void;

    public function getTracksUpdatedAt(): ?DateTimeInterface;

    public function setTracksUpdatedAtNow(): void;

    public function getPlaybacks(): Collection;

    public function getLastWeeklyPlaylist(): ?PlaylistInterface;
}
