<?php

declare(strict_types=1);

namespace App\Models\Spotify;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Playback
 * @property int $id
 * @property string $track_id
 * @property int $user_id
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property DateTimeInterface $played_at
 *
 * @property User $user
 * @property Track $track
 */
class Playback extends BaseModel
{
    protected $table = 'spotify_playbacks';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }
}
