<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Track
 * @property string $id
 * @property array $available_markets
 * @property int $disc_number
 * @property int $duration_ms
 * @property bool $explicit
 * @property array $external_ids
 * @property array $external_urls
 * @property string $href
 * @property bool $is_local
 * @property bool $is_playable
 * @property string $name
 * @property int $popularity
 * @property string $preview_url
 * @property int $track_number
 * @property string $type
 * @property string $uri
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property string $album_id
 *
 * @property Album $album
 * @property Collection|Artist[] $artists
 * @property Collection|Playback[] $playbacks
 */
class Track extends BaseModel
{
    protected $table = 'spotify_tracks';

    public function album(): BelongsTo
    {
        return $this->belongsTo(
            Album::class,
            'album_id'
        );
    }

    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(
            Artist::class,
            'spotify_track_artist',
            'track_id',
            'artist_id'
        );
    }

    public function playbacks(): HasMany
    {
        return $this->hasMany(
            Playback::class,
            'track_id'
        );
    }
}
