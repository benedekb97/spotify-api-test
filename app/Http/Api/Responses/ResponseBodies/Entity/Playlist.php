<?php

declare(strict_types=1);

namespace App\Http\Api\Responses\ResponseBodies\Entity;

use Illuminate\Database\Eloquent\Collection;

class Playlist implements EntityInterface
{
    private ?bool $collaborative = null;

    private ?string $description = null;

    private ?ExternalUrl $externalUrl = null;

    private ?Followers $followers = null;

    private ?string $href = null;

    private ?string $id = null;

    /** @var Collection<Image> */
    private Collection $images;

    private ?string $name = null;

    private ?PublicUser $owner = null;

    private ?bool $public = null;

    private ?string $snapshotId = null;

    /** @var Collection<PlaylistTrack> */
    private Collection $tracks;

    private ?string $type = null;

    private ?string $uri = null;

    public function __construct()
    {
        $this->images = new Collection();
        $this->tracks = new Collection();
    }

    public function isCollaborative(): ?bool
    {
        return $this->collaborative;
    }

    public function setCollaborative(?bool $collaborative): void
    {
        $this->collaborative = $collaborative;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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

    public function getOwner(): ?PublicUser
    {
        return $this->owner;
    }

    public function setOwner(?PublicUser $owner): void
    {
        $this->owner = $owner;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): void
    {
        $this->public = $public;
    }

    public function getSnapshotId(): ?string
    {
        return $this->snapshotId;
    }

    public function setSnapshotId(?string $snapshotId): void
    {
        $this->snapshotId = $snapshotId;
    }

    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(PlaylistTrack $track): void
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
            'collaborative' => $this->collaborative,
            'description' => $this->description,
            'externalUrl' => isset($this->externalUrl) ? $this->externalUrl->toArray() : null,
            'followers' => isset($this->followers) ? $this->followers->toArray() : null,
            'href' => $this->href,
            'id' => $this->id,
            'images' => $this->images->map(fn (Image $i) => $i->toArray())->toArray(),
            'name' => $this->name,
            'owner' => isset($this->owner) ? $this->owner->toArray() : null,
            'public' => $this->public,
            'snapshotId' => $this->snapshotId,
            'tracks' => $this->tracks->map(fn (PlaylistTrack $pt) => $pt->toArray())->toArray(),
            'type' => $this->type,
            'uri' => $this->uri,
        ];
    }
}
