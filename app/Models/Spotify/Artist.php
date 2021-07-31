<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Artist
 * @property string $id
 * @property array $followers
 * @property array $genres
 * @property string $href
 * @property array $images
 * @property string $name
 * @property int $popularity
 * @property string $type
 * @property string $uri
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 *
 * @property Collection|Album[] $albums
 */
class Artist extends BaseModel
{
    protected $table = 'spotify_artists';

    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(
            Album::class,
            'spotify_album_artist',
            'album_id',
            'artist_id'
        );
    }
}
