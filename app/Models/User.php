<?php

namespace App\Models;

use App\Models\Spotify\Playback;
use DateTime;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @property string $email
 * @property string $password
 * @property string $spotify_access_token
 * @property string $spotify_refresh_token
 * @property DateTimeInterface $spotify_access_token_expiry
 * @property string $spotify_access_scope
 * @property string $spotify_id
 * @property bool $automatically_create_weekly_playlist
 *
 * @property Collection|Scope[] $scopes
 * @property Collection|Playback[] $playbacks
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopes(): BelongsToMany
    {
        return $this->belongsToMany(Scope::class, 'user_scope', 'user_id', 'scope_id');
    }

    public function playbacks(): HasMany
    {
        return $this->hasMany(Playback::class, 'user_id');
    }

    public function isLoggedInWithSpotify(): bool
    {
        return isset($this->spotify_access_token)
            && isset($this->spotify_refresh_token)
            && isset($this->spotify_access_token_expiry)
            && (new DateTime()) < (new DateTime($this->spotify_access_token_expiry));
    }

    public function needsReauthentication(): bool
    {
        return !$this->isLoggedInWithSpotify() || (new DateTime()) < (new DateTime($this->spotify_access_token_expiry));
    }
}
