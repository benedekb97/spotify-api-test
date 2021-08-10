<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack as PlaylistTrackEntity;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PlaylistTrack
 * @property Track $track
 * @property Playlist $playlist
 *
 * @property string $playlist_id
 * @property string $track_id
 * @property DateTimeInterface $added_at
 * @property string $added_by_user_id
 * @property bool $is_local
 */
class PlaylistTrack extends BaseModel
{
    public $table = 'spotify_playlist_track';

    public $incrementing = false;

    public $timestamps = false;

    public $primaryKey = null;

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    public static function createFromEntity(PlaylistTrackEntity $playlistTrack, string $playlistId): ?self
    {
        if ($playlistTrack->getTrack()->getId() === null) {
            return null;
        }

        $model = self::where('track_id', $playlistTrack->getTrack()->getId())
            ->where('playlist_id', $playlistId)
            ->first();

        if ($model === null) {
            $model = new self();
        }

        $model->track_id = $playlistTrack->getTrack()->getId();
        $model->playlist_id = $playlistId;
        $model->added_at = $playlistTrack->getAddedAt()->format('Y-m-d H:i:s');
        $model->added_by_user_id = $playlistTrack->getAddedBy()->getId();
        $model->is_local = $playlistTrack->isLocal();

        $model->save();

        return $model;
    }
}
