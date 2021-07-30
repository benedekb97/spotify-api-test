<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 *
 * @property Collection|Scope[] $scopes
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
}
