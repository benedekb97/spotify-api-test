<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property Collection|AlbumArtist[] $albumArtists
 * @property Collection|TrackArtist[] $trackArtists
 */
class Artist extends BaseModel
{
    protected $casts = [
        'followers' => 'array',
        'genres' => 'array',
        'images' => 'array',
    ];

    public $incrementing = false;

    protected $table = 'spotify_artists';

    public function albumArtists(): HasMany
    {
        return $this->hasMany(AlbumArtist::class);
    }

    public function trackArtists(): HasMany
    {
        return $this->hasMany(TrackArtist::class);
    }
}
