<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TrackArtist
 * @property string $track_id
 * @property string $artist_id
 *
 * @property Track $track
 * @property Artist $artist
 */
class TrackArtist extends Model
{
    protected $table = 'spotify_track_artist';

    public $timestamps = false;

    public $incrementing = false;

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
}
