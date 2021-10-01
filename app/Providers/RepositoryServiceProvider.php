<?php

declare(strict_types=1);

namespace App\Providers;

use App\Entities\Scope;
use App\Entities\Spotify\Album;
use App\Entities\Spotify\Artist;
use App\Entities\Spotify\Playback;
use App\Entities\Spotify\Playlist;
use App\Entities\Spotify\PlaylistTrack;
use App\Entities\Spotify\Track;
use App\Entities\User;
use App\Repositories\AlbumRepository;
use App\Repositories\ArtistRepository;
use App\Repositories\PlaybackRepository;
use App\Repositories\PlaylistRepository;
use App\Repositories\PlaylistTrackRepository;
use App\Repositories\ScopeRepository;
use App\Repositories\TrackRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    private const ENTITY_REPOSITORY_MAP = [
        Album::class => AlbumRepository::class,
        Artist::class => ArtistRepository::class,
        Playback::class => PlaybackRepository::class,
        Playlist::class => PlaylistRepository::class,
        PlaylistTrack::class => PlaylistTrackRepository::class,
        Scope::class => ScopeRepository::class,
        Track::class => TrackRepository::class,
        User::class => UserRepository::class,
    ];

    public function register(): void
    {
        foreach (self::ENTITY_REPOSITORY_MAP as $class => $repository) {
            $this->app->bind(
                $repository,
                function (Application $app) use ($repository, $class) {
                    return new $repository(
                        $app['em'],
                        $app['em']->getClassMetaData($class)
                    );
                }
            );

            $this->app->bind(
                $repository . 'Interface',
                function (Application $app) use ($repository, $class) {
                    return new $repository(
                        $app['em'],
                        $app['em']->getClassMetaData($class)
                    );
                }
            );
        }
    }
}
