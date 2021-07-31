<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AlbumArtist
 * @property string $album_id
 * @property string $artist_id
 *
 * @property Album $album
 * @property Artist $artist
 */
class AlbumArtist extends Model
{
    protected $table = 'spotify_album_artist';

    public $timestamps = false;
    public $incrementing = false;

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
}
