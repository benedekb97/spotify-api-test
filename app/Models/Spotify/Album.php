<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Album
 * @property string $id
 * @property array $available_markets
 * @property array $copyrights
 * @property array $external_ids
 * @property array $external_urls
 * @property array $genres
 * @property string $href
 * @property array $images
 * @property string $label
 * @property string $name
 * @property int $popularity
 * @property string $release_date
 * @property string $release_date_precision
 * @property array $restrictions
 * @property int $total_tracks
 * @property string $type
 * @property string $uri
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 *
 * @property Collection|Artist[] $artists
 * @property Collection|Track[] $tracks
 */
class Album extends BaseModel
{
    protected $table = 'spotify_albums';

    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(
            Artist::class,
            'spotify_album_artist',
            'album_id',
            'artist_id'
        );
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(
            Track::class,
            'album_id'
        );
    }
}
