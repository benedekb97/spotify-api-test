<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
 * @property Collection|TrackArtist[] $trackArtists
 * @property Collection|Playback[] $playbacks
 */
class Track extends BaseModel
{
    protected $casts = [
        'available_markets' => 'array',
        'external_ids' => 'array',
        'external_urls' => 'array',
    ];

    public $incrementing = false;

    protected $table = 'spotify_tracks';

    public function album(): BelongsTo
    {
        return $this->belongsTo(
            Album::class,
            'album_id'
        );
    }

    public function trackArtists(): HasMany
    {
        return $this->hasMany(TrackArtist::class);
    }

    public function playbacks(): HasMany
    {
        return $this->hasMany(
            Playback::class,
            'track_id'
        );
    }
}
