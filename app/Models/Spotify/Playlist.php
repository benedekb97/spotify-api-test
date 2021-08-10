<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use App\Http\Api\Responses\ResponseBodies\Entity\Playlist as PlaylistEntity;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Playlist
 * @property string $id
 * @property bool $collaborative
 * @property string $description
 * @property array $external_url
 * @property array $followers
 * @property string $href
 * @property array $images
 * @property string $name
 * @property string $owner_user_id
 * @property string $snapshot_id
 * @property string $type
 * @property string $uri
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property int $local_user_id
 *
 * @property Collection|PlaylistTrack[] $playlistTracks
 * @property User|null $localUser
 */
class Playlist extends BaseModel
{
    public $table = 'spotify_playlists';

    public $incrementing = false;

    protected $casts = [
        'external_url' => 'array',
        'followers' => 'array',
        'images' => 'array'
    ];

    public function playlistTracks(): HasMany
    {
        return $this->hasMany(PlaylistTrack::class);
    }

    public function localUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'local_user_id');
    }

    public static function createFromEntity(PlaylistEntity $playlist): self
    {
        $model = self::find($playlist->getId());

        if ($model === null) {
            $model = new self();
        }

        if (($ownerId = $playlist->getOwner()->getId()) !== null) {
            /** @var User $user */
            $user = User::where('spotify_id', $ownerId)->first();

            if ($user !== null) {
                $model->local_user_id = $user->id;
            }
        }

        $model->id = $playlist->getId();
        $model->collaborative = $playlist->isCollaborative();
        $model->description = $playlist->getDescription();
        $model->external_url = $playlist->getExternalUrl()->toArray();
        $model->followers = $playlist->getFollowers() !== null ? $playlist->getFollowers()->toArray() : [];
        $model->href = $playlist->getHref();
        $model->images = $playlist->getImages() !== null ? $playlist->getImages()->toArray() : [];
        $model->name = $playlist->getName();
        $model->owner_user_id = $playlist->getOwner()->getId();
        $model->snapshot_id = $playlist->getSnapshotId();
        $model->type = $playlist->getType();
        $model->uri = $playlist->getUri();

        $model->save();

        return $model;
    }
}
